<?php

namespace App\Services;

use App\Models\Tracing;
use App\Models\IncidenceType;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractIncidence;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use InvalidArgumentException;

class TrainingContractCommunicationService
{
    private const TYPES = [
        'guide' => 'company_tutor_guide',
        'compliance' => 'training_compliance_notice',
        'noncompliance' => 'training_noncompliance_notice',
    ];

    private const STUDENT_TYPES = [
        'compliance' => 'training_compliance_student_notice',
        'noncompliance' => 'training_noncompliance_student_notice',
    ];

    public function __construct(
        private EmailTemplateService $emailTemplateService,
        private EmailDeliveryService $emailDeliveryService,
        private MoodleMailDeliveryService $moodleMailDeliveryService
    ) {
    }

    public function preview(TrainingContract $contract, string $type): array
    {
        $context = $this->resolveContext($contract, $type);
        $rendered = $this->emailTemplateService->render(
            $context['main_company_id'],
            $context['template_type'],
            $context['variables'],
            null,
            null,
            $context['web_platform_id']
        );
        $studentRendered = isset(self::STUDENT_TYPES[$type])
            ? $this->emailTemplateService->render(
                $context['main_company_id'],
                self::STUDENT_TYPES[$type],
                $context['variables'],
                null,
                null,
                $context['web_platform_id']
            )
            : null;

        return array_merge($rendered, [
            'type' => $type,
            'course' => [
                'id' => $context['course']->id,
                'name' => $this->courseLabel($context['course']),
            ],
            'logical_recipients' => $context['logical_recipients'],
            'final_recipient' => $this->forcedRecipient(),
            'attachment_name' => $type === 'guide' ? '7_GUIA TUTOR LABORAL.pdf' : null,
            'moodle_redirected_to_smtp' => (bool) config('mail.force_moodle_to_smtp', false),
            'company_message' => $rendered,
            'student_message' => $studentRendered,
        ]);
    }

    public function send(
        TrainingContract $contract,
        string $type,
        string $confirmationToken,
        ?string $subject,
        ?string $bodyHtml,
        ?string $studentSubject,
        ?string $studentBodyHtml,
        int $userId
    ): array {
        $context = $this->resolveContext($contract, $type);
        $templateArguments = [
            $context['main_company_id'],
            $context['template_type'],
            $context['variables'],
            (string) config('mail.from.name', 'Zona Avz'),
            $subject,
            $bodyHtml,
            $context['web_platform_id'],
        ];
        $studentTemplateArguments = isset(self::STUDENT_TYPES[$type]) ? [
            $context['main_company_id'],
            self::STUDENT_TYPES[$type],
            $context['variables'],
            (string) config('mail.from.name', 'Zona Avz'),
            $studentSubject,
            $studentBodyHtml,
            $context['web_platform_id'],
        ] : null;

        if ($type === 'guide') {
            $mail = $this->emailTemplateService->makeMailable(...$templateArguments);
            $attachmentPath = resource_path('mail-attachments/7_GUIA TUTOR LABORAL.pdf');
            if (!is_file($attachmentPath)) {
                throw new InvalidArgumentException('No se ha encontrado la Guía del Tutor Laboral para adjuntarla.');
            }
            $mail->attach($attachmentPath, ['as' => '7_GUIA TUTOR LABORAL.pdf', 'mime' => 'application/pdf']);
            $this->sendSmtp($context, $mail, $context['company']->email, 'company', $confirmationToken);

            $this->recordHistory($context, $type, $userId);

            return ['deliveries' => 1];
        }

        $this->sendSmtp(
            $context,
            $this->emailTemplateService->makeMailable(...$templateArguments),
            $context['company']->email,
            'company',
            $confirmationToken
        );

        if (config('mail.force_moodle_to_smtp', false)) {
            $this->sendSmtp(
                $context,
                $this->emailTemplateService->makeMailable(...$studentTemplateArguments),
                $context['student']->email,
                'student_moodle_redirect',
                $confirmationToken,
                'moodle_redirected_smtp',
                self::STUDENT_TYPES[$type]
            );
        } else {
            $this->moodleMailDeliveryService->sendTo(
                $context['tracing'],
                $this->emailTemplateService->makeMailable(...$studentTemplateArguments),
                [
                    'mail_type' => self::STUDENT_TYPES[$type],
                    'tracing_id' => $context['tracing']->id,
                    'student_id' => $context['student']->id,
                    'course_id' => $context['course']->id,
                    'main_company_id' => $context['main_company_id'],
                    'training_contract_id' => $contract->id,
                    'idempotency_suffix' => $confirmationToken,
                ]
            );
        }

        $this->recordHistory($context, $type, $userId);

        return ['deliveries' => 2];
    }

    private function resolveContext(TrainingContract $contract, string $type): array
    {
        if (!isset(self::TYPES[$type])) {
            throw new InvalidArgumentException('Tipo de comunicación no soportado.');
        }

        $contract->loadMissing(['company', 'student']);
        if (!$contract->company || trim((string) $contract->company->email) === '') {
            throw new InvalidArgumentException('La empresa no tiene un correo electrónico configurado.');
        }
        if (!$contract->student) {
            throw new InvalidArgumentException('El contrato no tiene un alumno configurado.');
        }

        $today = Carbon::today()->toDateString();
        $elements = TrainingContractElement::query()
            ->where('training_contract_id', $contract->id)
            ->where('main_company_id', $contract->main_company_id)
            ->whereNotNull('course_id')
            ->whereHas('course', function ($query) use ($today) {
                $query->whereDate('beginning', '<=', $today)->whereDate('end', '>=', $today);
            })
            ->with(['course.trainingAction', 'course.webPlatform', 'course.trainingAction.webPlatform'])
            ->get();

        if ($elements->isEmpty()) {
            throw new InvalidArgumentException('El contrato no tiene ningún curso activo en la fecha actual.');
        }
        if ($elements->count() > 1) {
            throw new InvalidArgumentException('El contrato tiene más de un curso activo. Corrija las fechas antes de enviar.');
        }

        $element = $elements->first();
        $course = $element->course;
        $tracings = Tracing::query()
            ->where('course_id', $course->id)
            ->where('student_id', $contract->student_id)
            ->where('company_id', $contract->company_id)
            ->where('main_company_id', $contract->main_company_id)
            ->where(function ($query) use ($element) {
                $query->where('training_contract_element_id', $element->id)
                    ->orWhereNull('training_contract_element_id');
            })
            ->with(['student', 'course.teacher', 'course.webPlatform', 'course.trainingAction.webPlatform'])
            ->get();

        if ($tracings->count() !== 1) {
            throw new InvalidArgumentException($tracings->isEmpty()
                ? 'El curso activo no tiene un seguimiento asociado al alumno.'
                : 'El curso activo tiene más de un seguimiento asociado al alumno.');
        }

        $tracing = $tracings->first();
        if ($type !== 'guide' && config('mail.force_moodle_to_smtp', false) && trim((string) $contract->student->email) === '') {
            throw new InvalidArgumentException('El alumno no tiene correo electrónico para realizar la prueba.');
        }

        $studentName = trim($contract->student->name.' '.$contract->student->surname);
        $companyTutorName = trim((string) $contract->company_tutor) ?: 'Tutor/a laboral';
        $webPlatform = $course->webPlatform ?: $course->trainingAction?->webPlatform;

        if ($type !== 'guide' && !config('mail.force_moodle_to_smtp', false)) {
            if (($course->moodle_mode ?? 'disabled') === 'disabled' || ($course->moodle_sync_status ?? 'disconnected') !== 'synced') {
                throw new InvalidArgumentException('El curso activo no está conectado y validado con Moodle.');
            }
            if (trim((string) $contract->student->user) === '') {
                throw new InvalidArgumentException('El alumno no tiene usuario de Moodle configurado.');
            }
            if (trim((string) $course->teacher?->user) === '') {
                throw new InvalidArgumentException('El profesor del curso no tiene usuario de Moodle configurado.');
            }
            if (!$webPlatform || trim((string) $webPlatform->url) === '' || trim((string) $webPlatform->token) === '') {
                throw new InvalidArgumentException('El curso activo no tiene una plataforma Moodle con URL y token.');
            }
        }

        return [
            'template_type' => self::TYPES[$type],
            'main_company_id' => (int) $contract->main_company_id,
            'training_contract_id' => (int) $contract->id,
            'company' => $contract->company,
            'student' => $contract->student,
            'course' => $course,
            'tracing' => $tracing,
            'web_platform_id' => $webPlatform?->id,
            'variables' => [
                'student_name' => $studentName,
                'company_tutor_name' => $companyTutorName,
            ],
            'logical_recipients' => $type === 'guide'
                ? [['channel' => 'Correo externo empresa', 'recipient' => $contract->company->email]]
                : [
                    ['channel' => 'Correo externo empresa', 'recipient' => $contract->company->email],
                    ['channel' => 'Moodle alumno', 'recipient' => $contract->student->user ?: $contract->student->email],
                ],
        ];
    }

    private function sendSmtp(
        array $context,
        Mailable $mail,
        string $recipient,
        string $recipientKind,
        string $confirmationToken,
        string $channel = 'smtp',
        ?string $mailType = null
    ): void {
        $this->emailDeliveryService->sendTo($recipient, $mail, [
            'mail_type' => ($mailType ?: $context['template_type']).':'.$recipientKind,
            'tracing_id' => $context['tracing']->id,
            'course_id' => $context['course']->id,
            'student_id' => $context['student']->id,
            'training_contract_id' => $context['training_contract_id'],
            'main_company_id' => $context['main_company_id'],
            'channel' => $channel,
            'idempotency_key' => hash('sha256', implode(':', [
                'training-contract-communication',
                $context['training_contract_id'],
                $context['template_type'],
                $recipientKind,
                $confirmationToken,
            ])),
        ]);
    }

    private function courseLabel($course): string
    {
        return trim(implode(' - ', array_filter([
            $course->trainingAction?->formative_action,
            $course->trainingAction?->name,
            $course->group,
        ]))) ?: (string) $course->name;
    }

    private function recordHistory(array $context, string $type, int $userId): void
    {
        $labels = [
            'guide' => 'Guía del tutor laboral enviada',
            'compliance' => 'Cumplimiento de formación enviado',
            'noncompliance' => 'Incumplimiento de formación enviado',
        ];
        $incidenceType = IncidenceType::firstOrCreate(['name' => 'Correo enviado']);

        TrainingContractIncidence::create([
            'affair' => $labels[$type],
            'notes' => 'Curso: '.$this->courseLabel($context['course']).'. Destino de prueba: '.($this->forcedRecipient() ?: 'destinatarios reales').'.',
            'incidence_type_id' => $incidenceType->id,
            'training_contract_id' => $context['training_contract_id'],
            'user_id' => $userId,
            'main_company_id' => $context['main_company_id'],
        ]);
    }

    private function forcedRecipient(): ?string
    {
        $recipient = trim((string) config('mail.force_to.address', ''));
        return $recipient !== '' ? $recipient : null;
    }
}

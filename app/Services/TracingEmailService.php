<?php

namespace App\Services;

use App\Models\Tracing;
use App\Models\TrainingContractElement;
use Carbon\Carbon;
use InvalidArgumentException;

class TracingEmailService
{
    public function __construct(
        private MoodleMailDeliveryService $moodleMailDeliveryService,
        private EmailTemplateService $emailTemplateService
    ) {
    }

    public function sendTracingMail(Tracing $tracing, string $type, array $options = []): Tracing
    {
        $tracing->loadMissing([
            'student:id,name,surname,email,user,password',
            'training_contract_element:id,training_tutor,training_tutor_dni',
            'course:id,name,group,beginning,end,welcome_date,quarter_date,half_date,three_quarters_date,final_date,main_company_id,training_action_id,teacher_id',
            'course.teacher:id,name,surname,user',
            'course.trainingAction:id,formative_action,name,total_hours,training_tutor,web_platform_id',
            'course.trainingAction.webPlatform:id,url,token',
        ]);

        $student = $tracing->student;
        $course = $tracing->course;

        if (!$student || !$course) {
            throw new InvalidArgumentException('El seguimiento no tiene alumno o curso configurado.');
        }

        $mainCompanyId = $tracing->main_company_id ?? $course->main_company_id;

        return match ($type) {
            'welcome' => $this->sendWelcome($tracing, $mainCompanyId, $options),
            'quarter' => $this->sendMilestone($tracing, 'quarter', '25%', 'quarter_message', 'quarter_date_sent', $course->quarter_date, $mainCompanyId, $options),
            'half' => $this->sendMilestone($tracing, 'half', '50%', 'half_message', 'half_date_sent', $course->half_date, $mainCompanyId, $options),
            'three_quarters' => $this->sendMilestone($tracing, 'three_quarters', '75%', 'three_quarters_message', 'three_quarters_date_sent', $course->three_quarters_date, $mainCompanyId, $options),
            'final' => $this->sendMilestone($tracing, 'final', 'Fin de curso', 'final_message', 'final_date_sent', $course->final_date, $mainCompanyId, $options),
            'one_week' => $this->sendOneWeekReminder($tracing, $mainCompanyId, $options),
            default => throw new InvalidArgumentException('Tipo de correo no soportado.'),
        };
    }

    private function sendWelcome(Tracing $tracing, ?int $mainCompanyId, array $options = []): Tracing
    {
        $student = $tracing->student;
        $course = $tracing->course;
        $trainingAction = $course->trainingAction;
        $tutorName = $this->resolveTutorName($tracing);
        if ($tutorName === null) {
            throw new InvalidArgumentException('No se enviará el correo porque falta configurar el tutor.');
        }

        $messageId = $this->moodleMailDeliveryService->sendTo(
            $tracing,
            $this->emailTemplateService->makeMailable(
                $mainCompanyId,
                'greeting',
                array_merge($this->templateVariables($tracing, $tutorName), [
                    'total_hours' => $trainingAction?->total_hours !== null ? (string) $trainingAction->total_hours : '-',
                    'course_start_date' => $course->beginning ? Carbon::parse($course->beginning)->format('d-m-Y') : '-',
                ]),
                $tutorName,
                $options['subject'] ?? null,
                $options['body_html'] ?? null
            ),
            [
                'mail_type' => 'greeting',
                'tracing_id' => $tracing->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'main_company_id' => $mainCompanyId,
                'idempotency_suffix' => $options['idempotency_suffix'] ?? null,
            ]
        );

        if ($messageId <= 0) {
            return $tracing->fresh();
        }

        $tracing->update([
            'welcome_message' => 1,
            'welcome_date_sent' => Carbon::today()->toDateString(),
        ]);

        return $tracing->fresh();
    }

    private function buildSubjectCode($course): string
    {
        $parts = array_filter([
            $course->trainingAction?->formative_action,
            $course->group,
        ], fn ($value) => filled($value));

        return implode('/', $parts);
    }

    private function buildFormativeActionLabel($course): string
    {
        $trainingAction = $course->trainingAction;
        $formativeAction = trim((string) ($trainingAction?->formative_action ?? ''));
        $name = trim((string) ($trainingAction?->name ?? ''));

        if ($name !== '') {
            return $name;
        }

        if ($formativeAction !== '') {
            return $formativeAction;
        }

        return (string) $course->name;
    }

    private function templateVariables(Tracing $tracing, string $tutorName): array
    {
        $student = $tracing->student;
        $course = $tracing->course;

        return [
            'student_name' => trim(implode(' ', array_filter([$student?->name, $student?->surname]))),
            'formative_action' => $this->buildFormativeActionLabel($course),
            'tutor_name' => $tutorName,
            'subject_code' => $this->buildSubjectCode($course),
            'course_end_date' => $course?->end ? Carbon::parse($course->end)->format('d-m-Y') : '-',
        ];
    }

    private function resolveTutorName(Tracing $tracing): ?string
    {
        $tracingTutor = trim((string) ($tracing->training_contract_element?->training_tutor ?? ''));
        if ($tracingTutor !== '') {
            return $tracingTutor;
        }

        $elementTutor = trim((string) TrainingContractElement::query()
            ->where('course_id', $tracing->course_id)
            ->where('main_company_id', $tracing->main_company_id)
            ->orderByDesc('id')
            ->value('training_tutor'));
        if ($elementTutor !== '') {
            return $elementTutor;
        }

        $course = $tracing->course;
        $teacherName = trim(implode(' ', array_filter([
            $course->teacher?->name,
            $course->teacher?->surname,
        ])));
        if ($teacherName !== '') {
            return $teacherName;
        }

        return null;
    }

    private function sendMilestone(
        Tracing $tracing,
        string $mailType,
        string $label,
        string $messageField,
        string $dateField,
        $milestoneDate,
        ?int $mainCompanyId,
        array $options = []
    ): Tracing {
        $student = $tracing->student;
        $course = $tracing->course;
        $tutorName = $this->resolveTutorName($tracing);
        if ($tutorName === null) {
            throw new InvalidArgumentException('No se enviará el correo porque falta configurar el tutor.');
        }

        $messageId = $this->moodleMailDeliveryService->sendTo(
            $tracing,
            $this->emailTemplateService->makeMailable(
                $mainCompanyId,
                $mailType,
                array_merge($this->templateVariables($tracing, $tutorName), [
                    'milestone_label' => $label,
                    'milestone_date' => $milestoneDate ? Carbon::parse($milestoneDate)->format('d-m-Y') : '-',
                    'final_result' => ($options['final_result'] ?? 'apto') === 'no_apto' ? 'NO APTO' : 'APTO',
                    'final_intro' => ($options['final_result'] ?? 'apto') === 'no_apto'
                        ? 'Le informamos de que el curso finaliza hoy y, tras revisar su actividad en la plataforma, su calificación final es de NO APTO.'
                        : 'Te informamos de que has obtenido la calificación de APTO en el curso.',
                    'final_detail' => ($options['final_result'] ?? 'apto') === 'no_apto'
                        ? 'No se han alcanzado los requisitos de conexión, visualización de unidades y realización de evaluaciones establecidos para superar la formación.'
                        : '¡Enhorabuena por haber completado satisfactoriamente la formación!',
                ]),
                $tutorName,
                $options['subject'] ?? null,
                $options['body_html'] ?? null
            ),
            [
                'mail_type' => $mailType,
                'tracing_id' => $tracing->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'main_company_id' => $mainCompanyId,
                'idempotency_suffix' => $options['idempotency_suffix'] ?? null,
            ]
        );

        if ($messageId <= 0) {
            return $tracing->fresh();
        }

        $tracing->update([
            $messageField => 1,
            $dateField => Carbon::today()->toDateString(),
        ]);

        return $tracing->fresh();
    }

    private function sendOneWeekReminder(Tracing $tracing, ?int $mainCompanyId, array $options = []): Tracing
    {
        $student = $tracing->student;
        $course = $tracing->course;
        $tutorName = $this->resolveTutorName($tracing);
        if ($tutorName === null) {
            throw new InvalidArgumentException('No se enviará el correo porque falta configurar el tutor.');
        }

        $messageId = $this->moodleMailDeliveryService->sendTo(
            $tracing,
            $this->emailTemplateService->makeMailable(
                $mainCompanyId,
                'course_end_reminder',
                $this->templateVariables($tracing, $tutorName),
                $tutorName,
                $options['subject'] ?? null,
                $options['body_html'] ?? null
            ),
            [
                'mail_type' => 'course_end_reminder',
                'tracing_id' => $tracing->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'main_company_id' => $mainCompanyId,
                'idempotency_suffix' => $options['idempotency_suffix'] ?? null,
            ]
        );

        if ($messageId <= 0) {
            return $tracing->fresh();
        }

        $tracing->update([
            'one_week_message' => 1,
            'one_week_date_sent' => Carbon::today()->toDateString(),
        ]);

        return $tracing->fresh();
    }
}

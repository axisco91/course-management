<?php

namespace App\Console\Commands;

use App\Helpers\GeneralHelpers;
use App\Mail\GreetingMessageMail;
use App\Models\Tracing;
use App\Models\TrainingContractElement;
use App\Services\EmailDeliveryService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GreetingMessage extends Command
{
    public function __construct(private EmailDeliveryService $emailDeliveryService)
    {
        parent::__construct();
    }

    protected $signature = 'greetingMessage';

    protected $description = 'Envio automatico del correo de bienvenida en la fecha de inicio del seguimiento';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $sent = 0;
        $skipped = 0;
        $failed = 0;

        Tracing::query()
            ->with([
                'student:id,name,surname,email,user,password',
                'training_contract_element:id,training_tutor,training_tutor_dni',
                'course:id,name,group,beginning,end,welcome_date,main_company_id,training_action_id,teacher_id',
                'course.teacher:id,name,surname',
                'course.trainingAction:id,formative_action,name,total_hours,training_tutor',
            ])
            ->where(function ($query) {
                $query->whereNull('welcome_message')
                    ->orWhere('welcome_message', 0);
            })
            ->whereNull('welcome_date_sent')
            ->whereHas('course', function ($query) use ($today) {
                $query->whereDate('welcome_date', $today)
                    ->orWhere(function ($subQuery) use ($today) {
                        $subQuery->whereNull('welcome_date')
                            ->whereDate('beginning', $today);
                    });
            })
            ->chunkById(100, function ($tracings) use ($today, &$sent, &$skipped, &$failed) {
                foreach ($tracings as $tracing) {
                    $student = $tracing->student;
                    $course = $tracing->course;

                    if (!$student || !$course || empty($student->email)) {
                        $skipped++;
                        continue;
                    }

                    $mainCompanyId = $tracing->main_company_id ?? $course->main_company_id;
                    $courseStartDate = $course->beginning ? Carbon::parse($course->beginning)->format('d-m-Y') : null;
                    $courseEndDate = $course->end ? Carbon::parse($course->end)->format('d-m-Y') : null;
                    $trainingAction = $course->trainingAction;
                    $tutorName = $this->resolveTutorName($tracing);

                    if ($tutorName === null) {
                        $skipped++;
                        Log::warning('Greeting message skipped because tutor is missing', [
                            'tracing_id' => $tracing->id,
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                        ]);
                        continue;
                    }

                    try {
                        $this->emailDeliveryService->sendTo(
                            $student->email,
                            new GreetingMessageMail(
                                $this->buildSubjectCode($course),
                                $this->buildFormativeActionLabel($course),
                                $tutorName,
                                $trainingAction?->total_hours !== null ? (string) $trainingAction->total_hours : null,
                                $courseStartDate,
                                $courseEndDate
                            ),
                            [
                                'mail_type' => 'greeting',
                                'tracing_id' => $tracing->id,
                                'student_id' => $student->id,
                                'course_id' => $course->id,
                                'main_company_id' => $mainCompanyId,
                            ]
                        );

                        $tracing->update([
                            'welcome_message' => 1,
                            'welcome_date_sent' => $today,
                        ]);

                        $sent++;
                    } catch (\Throwable $e) {
                        $failed++;
                        Log::error('Error sending greeting message', [
                            'tracing_id' => $tracing->id,
                            'student_id' => $student->id,
                            'email' => $student->email,
                            'message' => $e->getMessage(),
                        ]);
                    }
                }
            });

        $this->info("Greeting emails processed. Sent: {$sent}, skipped: {$skipped}, failed: {$failed}");

        return Command::SUCCESS;
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

        if ($formativeAction !== '' && $name !== '') {
            return $formativeAction.': '.$name;
        }

        if ($formativeAction !== '') {
            return $formativeAction;
        }

        if ($name !== '') {
            return $name;
        }

        return (string) $course->name;
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
}

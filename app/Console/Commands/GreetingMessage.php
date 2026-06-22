<?php

namespace App\Console\Commands;

use App\Helpers\GeneralHelpers;
use App\Mail\GreetingMessageMail;
use App\Models\MainCompany;
use App\Models\Tracing;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GreetingMessage extends Command
{
    protected $signature = 'greetingMessage';

    protected $description = 'Envio automatico del correo de bienvenida en la fecha de inicio del seguimiento';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $sent = 0;
        $skipped = 0;
        $failed = 0;

        $username = GeneralHelpers::generalSettingValue('email');
        $emailPassword = GeneralHelpers::generalSettingValue('password');

        if ($username && $emailPassword) {
            config([
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $emailPassword,
            ]);
        }

        Tracing::query()
            ->with([
                'student:id,name,surname,email,user,password',
                'course:id,name,group,beginning,end,welcome_date,main_company_id,training_action_id',
                'course.trainingAction:id,web_platform_id',
                'course.trainingAction.webPlatform:id,url',
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
                    $mainCompany = $mainCompanyId ? MainCompany::find($mainCompanyId) : null;

                    $platformName = $mainCompany?->name ?? config('app.name', 'Plataforma');
                    $platformUrl = $course->trainingAction?->webPlatform?->url
                        ?? $mainCompany?->url
                        ?? config('app.url');
                    $studentName = trim(($student->name ?? '').' '.($student->surname ?? ''));
                    $courseStartDate = $course->beginning ? Carbon::parse($course->beginning)->format('d-m-Y') : null;
                    $courseEndDate = $course->end ? Carbon::parse($course->end)->format('d-m-Y') : null;
                    $platformUsername = $student->user ?? null;
                    $platformPassword = $student->password ?? null;

                    try {
                        Mail::mailer('smtp')
                            ->to($student->email)
                            ->send(
                                new GreetingMessageMail(
                                    $studentName !== '' ? $studentName : 'Alumno/a',
                                    (string) $course->name,
                                    $course->group,
                                    $courseStartDate,
                                    $courseEndDate,
                                    (string) $platformName,
                                    $platformUrl,
                                    $platformUsername,
                                    $platformPassword
                                )
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
}

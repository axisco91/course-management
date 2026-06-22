<?php

namespace App\Console\Commands;

use App\Helpers\GeneralHelpers;
use App\Mail\CourseEndReminderMail;
use App\Models\Tracing;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CourseEndReminderMessage extends Command
{
    protected $signature = 'courseEndReminderMessage';

    protected $description = 'Envia recordatorio al alumno una semana antes de finalizar el curso';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $targetEndDate = Carbon::today()->addDays(7)->toDateString();
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
                'student:id,name,surname,email',
                'course:id,name,end',
            ])
            ->where(function ($query) {
                $query->whereNull('one_week_message')
                    ->orWhere('one_week_message', 0);
            })
            ->whereNull('one_week_date_sent')
            ->whereHas('course', function ($query) use ($targetEndDate) {
                $query->whereDate('end', $targetEndDate);
            })
            ->chunkById(100, function ($tracings) use ($today, &$sent, &$skipped, &$failed) {
                foreach ($tracings as $tracing) {
                    $student = $tracing->student;
                    $course = $tracing->course;

                    if (!$student || !$course || empty($student->email) || empty($course->end)) {
                        $skipped++;
                        continue;
                    }

                    $studentName = trim(($student->name ?? '').' '.($student->surname ?? ''));
                    $courseEndDate = Carbon::parse($course->end)->format('d-m-Y');

                    try {
                        Mail::mailer('smtp')
                            ->to($student->email)
                            ->send(new CourseEndReminderMail(
                                $studentName !== '' ? $studentName : 'Alumno/a',
                                (string) $course->name,
                                $courseEndDate
                            ));

                        $tracing->update([
                            'one_week_message' => 1,
                            'one_week_date_sent' => $today,
                        ]);

                        $sent++;
                    } catch (\Throwable $e) {
                        $failed++;
                        Log::error('Error sending course end reminder', [
                            'tracing_id' => $tracing->id,
                            'student_id' => $student->id ?? null,
                            'email' => $student->email ?? null,
                            'message' => $e->getMessage(),
                        ]);
                    }
                }
            });

        $this->info("Course end reminder processed. Sent: {$sent}, skipped: {$skipped}, failed: {$failed}");

        return Command::SUCCESS;
    }
}

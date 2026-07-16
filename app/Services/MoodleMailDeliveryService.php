<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\Tracing;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class MoodleMailDeliveryService
{
    public function __construct(private MoodleLocalMailClient $client)
    {
    }

    public function sendTo(Tracing $tracing, Mailable $mailable, array $context = []): int
    {
        $tracing->loadMissing([
            'student',
            'course.teacher:id,user',
            'course.trainingAction.webPlatform',
        ]);

        $student = $tracing->student;
        $course = $tracing->course;
        $webPlatform = $course?->trainingAction?->webPlatform;
        $recipientUsername = trim((string) ($student?->user ?? ''));
        $senderUsername = trim((string) ($course?->teacher?->user ?? ''));
        $courseShortname = $this->resolveCourseShortname($course);

        if (!$student || $recipientUsername === '') {
            throw new InvalidArgumentException('El alumno no tiene usuario de Moodle configurado.');
        }

        if (!$course || $courseShortname === '') {
            throw new InvalidArgumentException('No se ha podido obtener el curso de Moodle.');
        }

        if ($senderUsername === '') {
            throw new InvalidArgumentException('El profesor del curso no tiene usuario de Moodle configurado.');
        }

        if (!$webPlatform || empty($webPlatform->url) || empty($webPlatform->token)) {
            throw new InvalidArgumentException('El curso no tiene una plataforma Moodle con URL y token.');
        }

        $mailType = (string) ($context['mail_type'] ?? class_basename($mailable));
        $idempotencySeed = 'tracing:'.$tracing->id.':'.$mailType.':sender:'.$senderUsername;
        if (!empty($context['idempotency_suffix'])) {
            $idempotencySeed .= ':'.$context['idempotency_suffix'];
        }
        $idempotencyKey = hash('sha256', $idempotencySeed);
        $previousLog = EmailLog::where('idempotency_key', $idempotencyKey)->first();

        if ($previousLog && $previousLog->status === 'sent' && $previousLog->moodle_message_id) {
            return (int) $previousLog->moodle_message_id;
        }

        $html = $mailable->render();
        $subject = trim((string) $mailable->subject);
        $text = $this->htmlToText($html);
        $dryRun = (bool) config('mail.dry_run', false);

        $log = EmailLog::updateOrCreate(
            ['idempotency_key' => $idempotencyKey],
            [
                'mail_type' => $mailType,
                'mailable' => get_class($mailable),
                'subject' => $subject,
                'original_to' => (string) ($student->email ?? ''),
                'final_to' => $recipientUsername,
                'channel' => 'moodle_local_mail',
                'status' => $dryRun ? 'simulated' : 'pending',
                'error_message' => null,
                'is_dry_run' => $dryRun,
                'sent_at' => null,
                'moodle_message_id' => null,
                'tracing_id' => $context['tracing_id'] ?? $tracing->id,
                'course_id' => $context['course_id'] ?? $course->id,
                'student_id' => $context['student_id'] ?? $student->id,
                'main_company_id' => $context['main_company_id'] ?? $tracing->main_company_id ?? $course->main_company_id,
            ]
        );

        if ($dryRun) {
            return 0;
        }

        try {
            $result = $this->client->send((string) $webPlatform->url, (string) $webPlatform->token, [
                'idempotencykey' => $idempotencyKey,
                'courseshortname' => $courseShortname,
                'senderusername' => $senderUsername,
                'recipientusername' => $recipientUsername,
                'subject' => $subject,
                'bodytext' => 'base64:'.base64_encode($text),
                'bodyhtml' => 'base64:'.base64_encode($html),
            ]);

            $log->update([
                'status' => 'sent',
                'moodle_message_id' => $result['message_id'],
                'sent_at' => Carbon::now(),
                'error_message' => null,
            ]);

            return (int) $result['message_id'];
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'sent_at' => null,
            ]);

            Log::error('Moodle local_mail delivery failed', [
                'tracing_id' => $tracing->id,
                'mail_type' => $mailType,
                'course_shortname' => $courseShortname,
                'sender_username' => $senderUsername,
                'recipient_username' => $recipientUsername,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function resolveCourseShortname($course): string
    {
        if (!$course) {
            return '';
        }

        $parts = explode(' - ', (string) $course->name);
        $code = trim((string) ($parts[0] ?? ''));
        $group = trim((string) $course->group);

        if ($code === '' || $group === '') {
            return '';
        }

        return $code.'/'.$group;
    }

    private function htmlToText(string $html): string
    {
        $withLineBreaks = preg_replace('/<\s*br\s*\/?>/i', "\n", $html) ?? $html;
        $withLineBreaks = preg_replace('/<\/(div|p|li|tr|h[1-6])>/i', "\n", $withLineBreaks) ?? $withLineBreaks;
        $text = strip_tags($withLineBreaks);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace("/[ \t]+/", ' ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

        return trim($text);
    }
}

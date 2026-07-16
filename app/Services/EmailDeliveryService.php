<?php

namespace App\Services;

use App\Helpers\GeneralHelpers;
use App\Models\EmailLog;
use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailDeliveryService
{
    public function sendTo($to, Mailable $mailable, array $context = [], string $mailer = 'smtp'): void
    {
        $originalRecipients = $this->normalizeRecipients($to);
        $forcedRecipients = $this->normalizeRecipients(config('mail.force_to.address', ''));
        $dryRun = (bool) config('mail.dry_run', false);
        $finalRecipients = count($forcedRecipients) > 0 ? $forcedRecipients : $originalRecipients;

        if (count($finalRecipients) === 0) {
            throw new InvalidArgumentException('No hay destinatarios válidos para el envío.');
        }

        $this->configureDynamicSmtp();

        $payload = [
            'mail_type' => $context['mail_type'] ?? class_basename($mailable),
            'mailable' => get_class($mailable),
            'subject' => $this->resolveSubject($mailable),
            'original_to' => implode(', ', $originalRecipients),
            'final_to' => implode(', ', $finalRecipients),
            'status' => $dryRun ? 'simulated' : 'sent',
            'error_message' => null,
            'is_dry_run' => $dryRun,
            'sent_at' => Carbon::now(),
            'tracing_id' => $context['tracing_id'] ?? null,
            'course_id' => $context['course_id'] ?? null,
            'student_id' => $context['student_id'] ?? null,
            'main_company_id' => $context['main_company_id'] ?? null,
        ];

        if ($dryRun) {
            EmailLog::create($payload);
            return;
        }

        try {
            $pendingMail = Mail::mailer($mailer);

            if (count($finalRecipients) === 1) {
                $pendingMail->to($finalRecipients[0])->send($mailable);
            } else {
                $pendingMail->to($finalRecipients)->send($mailable);
            }

            EmailLog::create($payload);
        } catch (\Throwable $e) {
            EmailLog::create([
                'mail_type' => $payload['mail_type'],
                'mailable' => $payload['mailable'],
                'subject' => $payload['subject'],
                'original_to' => $payload['original_to'],
                'final_to' => $payload['final_to'],
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'is_dry_run' => false,
                'sent_at' => Carbon::now(),
                'tracing_id' => $payload['tracing_id'],
                'course_id' => $payload['course_id'],
                'student_id' => $payload['student_id'],
                'main_company_id' => $payload['main_company_id'],
            ]);

            Log::error('Email delivery failed', [
                'mail_type' => $payload['mail_type'],
                'original_to' => $payload['original_to'],
                'final_to' => $payload['final_to'],
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function configureDynamicSmtp(): void
    {
        $username = GeneralHelpers::generalSettingValue('email');
        $emailPassword = GeneralHelpers::generalSettingValue('password');

        if ($username && $emailPassword) {
            config([
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $emailPassword,
            ]);
        }
    }

    private function normalizeRecipients($to): array
    {
        if (is_string($to)) {
            return $this->splitAndFilterRecipients([$to]);
        }

        if (is_array($to)) {
            return $this->splitAndFilterRecipients(array_values(array_filter(array_map(static function ($value) {
                if (is_string($value)) {
                    return $value;
                }

                if (is_array($value) && isset($value['email']) && is_string($value['email'])) {
                    return $value['email'];
                }

                return null;
            }, $to))));
        }

        return [];
    }

    private function splitAndFilterRecipients(array $values): array
    {
        $recipients = [];

        foreach ($values as $value) {
            foreach (preg_split('/[;,]+/', (string) $value) ?: [] as $piece) {
                $email = trim($piece);

                if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = $email;
                }
            }
        }

        return array_values(array_unique($recipients));
    }

    private function resolveSubject(Mailable $mailable): ?string
    {
        try {
            $built = clone $mailable;
            $built->build();

            return $built->subject ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}

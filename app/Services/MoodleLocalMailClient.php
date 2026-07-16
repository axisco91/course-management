<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MoodleLocalMailClient
{
    public function send(string $baseUrl, string $token, array $message): array
    {
        if (!(bool) config('moodle_mail.enabled', true)) {
            throw new RuntimeException('El envío mediante local_mail está desactivado.');
        }

        $endpoint = rtrim($baseUrl, '/').'/webservice/rest/server.php';

        try {
            $response = Http::acceptJson()
                ->asForm()
                ->timeout((int) config('moodle_mail.timeout', 15))
                ->post($endpoint, array_merge($message, [
                    'wstoken' => $token,
                    'wsfunction' => (string) config('moodle_mail.function', 'local_zonaavz_send_mail'),
                    'moodlewsrestformat' => 'json',
                ]));
        } catch (ConnectionException $e) {
            throw new RuntimeException('No se ha podido conectar con Moodle.', 0, $e);
        }

        $data = $response->json();

        if (!$response->successful()) {
            $moodleMessage = is_array($data) ? trim((string) ($data['message'] ?? '')) : '';
            $errorCode = is_array($data) ? trim((string) ($data['errorcode'] ?? '')) : '';
            $detail = $moodleMessage;

            if ($detail === '') {
                $rawBody = html_entity_decode(strip_tags($response->body()), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $detail = trim((string) preg_replace('/\s+/', ' ', $rawBody));
                $detail = mb_substr($detail, 0, 1000);
            }

            if ($errorCode !== '') {
                $detail .= ($detail !== '' ? ' ' : '').'['.$errorCode.']';
            }

            if ($detail === '') {
                $diagnosticHeaders = collect([
                    'server' => $response->header('Server'),
                    'content-type' => $response->header('Content-Type'),
                    'www-authenticate' => $response->header('WWW-Authenticate'),
                    'x-mod-security-message' => $response->header('X-Mod-Security-Message'),
                    'cf-ray' => $response->header('CF-Ray'),
                ])->filter(fn ($value) => filled($value));

                if ($diagnosticHeaders->isNotEmpty()) {
                    $detail = $diagnosticHeaders
                        ->map(fn ($value, $name) => $name.'='.$value)
                        ->implode(', ');
                }
            }

            throw new RuntimeException(
                'Moodle ha respondido con HTTP '.$response->status().
                ($detail !== '' ? ': '.$detail : '.')
            );
        }

        if (!is_array($data)) {
            throw new RuntimeException('Moodle ha devuelto una respuesta no válida.');
        }

        if (isset($data['exception']) || isset($data['errorcode'])) {
            throw new RuntimeException((string) ($data['message'] ?? 'Moodle ha rechazado el mensaje.'));
        }

        $messageId = (int) ($data['messageid'] ?? 0);
        if ($messageId <= 0) {
            throw new RuntimeException('Moodle no ha confirmado la creación del mensaje.');
        }

        return [
            'message_id' => $messageId,
            'duplicate' => (bool) ($data['duplicate'] ?? false),
        ];
    }
}

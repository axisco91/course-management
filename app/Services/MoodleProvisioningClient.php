<?php

namespace App\Services;

use App\Models\WebPlatform;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MoodleProvisioningClient
{
    public function call(WebPlatform $platform, string $function, array $parameters = []): array
    {
        if (!$platform->url || !$platform->token) {
            throw new RuntimeException('La plataforma Moodle no tiene URL o token configurados.');
        }

        try {
            $response = Http::acceptJson()->asForm()->timeout(120)->post(
                rtrim($platform->url, '/').'/webservice/rest/server.php',
                array_merge($parameters, [
                    'wstoken' => $platform->token,
                    'wsfunction' => $function,
                    'moodlewsrestformat' => 'json',
                ])
            );
        } catch (ConnectionException $e) {
            throw new RuntimeException('No se ha podido conectar con Moodle.', 0, $e);
        }

        $data = $response->json();
        if (!$response->successful() || !is_array($data) || isset($data['exception']) || isset($data['errorcode'])) {
            $message = is_array($data) ? ($data['message'] ?? null) : null;
            throw new RuntimeException($message ?: 'Moodle ha rechazado la operación '.$function.'.');
        }

        return $data;
    }

    public function siteInfo(WebPlatform $platform): array
    {
        return $this->call($platform, 'local_zonaavz_site_info');
    }

    public function courses(WebPlatform $platform): array
    {
        try {
            $result = $this->call($platform, 'local_zonaavz_list_courses');
            return $result['courses'] ?? [];
        } catch (RuntimeException $e) {
            if (!$this->isMissingConnectorFunction($e)) {
                throw $e;
            }

            return collect($this->call($platform, 'core_course_get_courses'))
                ->filter(fn (array $course) => (int) ($course['id'] ?? 0) > 1)
                ->map(fn (array $course) => [
                    'id' => (int) $course['id'],
                    'fullname' => (string) ($course['fullname'] ?? ''),
                    'shortname' => (string) ($course['shortname'] ?? ''),
                    'idnumber' => (string) ($course['idnumber'] ?? ''),
                    'categoryid' => (int) ($course['categoryid'] ?? 0),
                    'startdate' => (int) ($course['startdate'] ?? 0),
                    'enddate' => (int) ($course['enddate'] ?? 0),
                    'visible' => (bool) ($course['visible'] ?? true),
                ])->values()->all();
        }
    }

    public function provision(WebPlatform $platform, array $payload): array
    {
        try {
            return $this->call($platform, 'local_zonaavz_provision_course', [
                'payload' => 'base64:'.base64_encode(json_encode($payload, JSON_UNESCAPED_UNICODE)),
            ]);
        } catch (RuntimeException $e) {
            if ($this->isAccessControlError($e)) {
                throw new RuntimeException(
                    'El token Moodle no tiene autorizada la función local_zonaavz_provision_course.',
                    0,
                    $e
                );
            }
            throw $e;
        }
    }

    public function assertUsersExist(WebPlatform $platform, array $usernames): void
    {
        if ($usernames === []) {
            return;
        }

        $found = collect();
        foreach ($usernames as $username) {
            $result = $this->call($platform, 'core_user_get_users', [
                'criteria' => [['key' => 'username', 'value' => $username]],
            ]);
            $found = $found->merge(collect($result['users'] ?? [])->pluck('username'));
        }

        $found = $found
            ->map(fn ($username) => mb_strtolower((string) $username))
            ->all();
        $missing = array_values(array_filter(
            $usernames,
            fn ($username) => !in_array(mb_strtolower((string) $username), $found, true)
        ));

        if ($missing !== []) {
            throw new RuntimeException(
                'Usuarios obligatorios no encontrados en Moodle: '.implode(', ', $missing).'.'
            );
        }
    }

    public function assertProvisioningAvailable(WebPlatform $platform): void
    {
        $siteInfo = $this->call($platform, 'core_webservice_get_site_info');
        $available = collect($siteInfo['functions'] ?? [])->pluck('name');
        if (!$available->contains('local_zonaavz_provision_course')) {
            throw new RuntimeException(
                'El servicio externo del token no incluye local_zonaavz_provision_course. Añade esta función en Moodle antes de activar la creación automática.'
            );
        }
    }

    private function isMissingConnectorFunction(RuntimeException $e): bool
    {
        $message = mb_strtolower($e->getMessage());

        return str_contains($message, 'external_functions')
            || str_contains($message, 'function does not exist')
            || str_contains($message, 'función no existe')
            || str_contains($message, 'access control exception')
            || str_contains($message, 'control de acceso');
    }

    private function isAccessControlError(RuntimeException $e): bool
    {
        $message = mb_strtolower($e->getMessage());

        return str_contains($message, 'access control exception')
            || str_contains($message, 'control de acceso');
    }
}

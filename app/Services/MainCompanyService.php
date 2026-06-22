<?php

namespace App\Services;

use App\Mail\Sendmail;
use App\Models\MainCompany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class MainCompanyService
{
    public function create(array $data): MainCompany
    {
        return DB::transaction(function () use ($data) {
            $mainCompany = new MainCompany();

            $payload = $this->buildPayload($mainCompany, $data);
            $mainCompany->fill($payload);
            $mainCompany->save();

            $host = $this->extractSubdomainHost($data['url'] ?? $mainCompany->url ?? null);
            if ($host !== null) {
                $this->requestSubdomainCommand($host, 'Hosting_Subdomain_Create');
            }

            return $mainCompany;
        });
    }

    public function update(MainCompany $mainCompany, array $data): MainCompany
    {
        $payload = $this->buildPayload($mainCompany, $data);
        $mainCompany->update($payload);

        return $mainCompany;
    }

    public function delete(MainCompany $mainCompany): bool
    {
        return DB::transaction(function () use ($mainCompany) {
            $host = $this->extractSubdomainHost($mainCompany->url);
            if ($host !== null) {
                $this->requestSubdomainCommand($host, 'Hosting_Subdomain_Delete');
            }

            return (bool) $mainCompany->delete();
        });
    }

    private function buildPayload(MainCompany $mainCompany, array $data): array
    {
        $allowed = [
            'name',
            'address',
            'phone',
            'email',
            'password',
            'url',
            'active',
            'title',
            'primary_color',
            'secondary_color',
            'success_color',
            'warning_color',
            'error_color',
        ];

        $payload = array_intersect_key($data, array_flip($allowed));

        if (array_key_exists('logo', $data)) {
            $payload['logo'] = $this->normalizeLogoPath($mainCompany, $data['logo']);
        }

        return $payload;
    }

    private function normalizeLogoPath(MainCompany $mainCompany, mixed $logo): ?string
    {
        if ($logo === null || $logo === '') {
            return null;
        }

        $uuid = $mainCompany->uuid ?: (string) $mainCompany->getAttribute('uuid');
        if (!$uuid) {
            $uuid = (string) Str::uuid();
            $mainCompany->uuid = $uuid;
        }

        if (!$uuid) {
            return null;
        }

        if ($logo instanceof UploadedFile) {
            $extension = strtolower($logo->getClientOriginalExtension() ?: $logo->extension() ?: 'jpg');
            $path = $uuid . '/logos/logo.' . $extension;

            Storage::disk('public')->putFileAs($uuid . '/logos', $logo, 'logo.' . $extension);

            return $path;
        }

        return (string) $logo;
    }

    private function requestSubdomainCommand(string $host, string $command): array
    {
        $apiUrl = config('services.dinahosting.api_url');
        if (!$apiUrl) {
            throw new RuntimeException('Dinahosting API URL no configurada');
        }

        $apiData = [
            'AUTH_USER' => config('services.dinahosting.auth_user'),
            'AUTH_PWD' => config('services.dinahosting.auth_pwd'),
            'hosting' => config('services.dinahosting.hosting'),
            'directory' => config('services.dinahosting.directory'),
            'host' => $host,
            'command' => $command,
            'responseType' => 'Json',
        ];

        $postFields = http_build_query($apiData);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            $err = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            throw new RuntimeException("cURL error ($errno): $err");
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new RuntimeException("HTTP error $httpCode: $result");
        }

        $response = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Respuesta JSON invalida: ' . json_last_error_msg());
        }

        return $response;
    }

    private function extractSubdomainHost(?string $rawUrl): ?string
    {
        $value = trim((string) $rawUrl);
        if ($value === '') {
            return null;
        }

        if (!str_contains($value, '://')) {
            $value = 'https://' . $value;
        }

        $host = parse_url($value, PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return null;
        }

        $host = strtolower(trim($host));
        $host = preg_replace('/\.labortime\.app$/', '', $host) ?? $host;

        if (str_contains($host, '.')) {
            $parts = explode('.', $host);
            $host = $parts[0] ?? $host;
        }

        $host = preg_replace('/[^a-z0-9-]/', '', $host) ?? '';

        return $host !== '' ? $host : null;
    }
}

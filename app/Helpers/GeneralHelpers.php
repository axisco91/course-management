<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\GeneralSetting;
use App\Models\MainCompany;
use App\Models\User;
use Carbon\Carbon;
use Mpdf\Tag\Main;

class GeneralHelpers
{

    /**
     * Get the number passed and convert the coma to a dot.
     */
    public static function convertComa($number){
        $number = str_replace(',', '.', $number);

        return $number;
    }

    public static function seconds_to_human_readable($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remaining_seconds = $seconds % 60;

        $formatted_time = '';
        if ($hours > 0) {
            $formatted_time .= $hours . ' h ';
        }
        if ($minutes > 0) {
            $formatted_time .= $minutes . ' m ';
        }
        if ($remaining_seconds > 0 || $formatted_time === '') {
            $formatted_time .= $remaining_seconds . ' s';
        }
        return trim($formatted_time);
    }

    public static function seconds_to_hours($seconds) {
        $hours = floor($seconds / 3600);

        return trim($hours);
    }

    public static function convertToMinutes(string $timeString): float {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        // Match the numbers followed by h, m, or s
        preg_match_all('/(\d+)\s*h?|\s*(\d+)\s*m?|\s*(\d+)\s*s?/', $timeString, $matches);

        foreach ($matches[0] as $match) {
            if (strpos($match, 'h') !== false) {
                $hours = (int) filter_var($match, FILTER_SANITIZE_NUMBER_INT);
            } elseif (strpos($match, 'm') !== false) {
                $minutes = (int) filter_var($match, FILTER_SANITIZE_NUMBER_INT);
            } elseif (strpos($match, 's') !== false) {
                $seconds = (int) filter_var($match, FILTER_SANITIZE_NUMBER_INT);
            }
        }

        return $hours * 60 + $minutes + $seconds / 60;
    }

    public static function generalSettingValue(string $element) {
        $generalSetting = GeneralSetting::where('data', $element)->first();

        if ($generalSetting) {
            return $generalSetting->value;
        } else {
            return null;
        }
    }

    public static function urlObtainCompanyId(?string $url, ?int $userId = null): ?int
    {
        $candidate = self::normalizeHostname($url);

        // Fix localhost in local environments by mapping it to the current main company host.
        if ($candidate === 'localhost') {
            $candidate = 'zona.academypro.app';
        }

        $mainCompany = null;
        if ($candidate) {
            // Buscar empresa por URL, normalizando también la columna en DB.
            $mainCompany = MainCompany::query()
                ->whereRaw(
                    "LOWER(TRIM(BOTH '/' FROM REPLACE(REPLACE(TRIM(url), 'https://', ''), 'http://', ''))) = ?",
                    [$candidate]
                )
                ->first();
        }

        if ($mainCompany) {
            return $mainCompany->id;
        }

        // Si no hay empresa por URL y tenemos userId, usar la del usuario
        if ($userId) {
            $user = User::find($userId);

            if ($user && $user->main_company_id) {
                return $user->main_company_id;
            }
        }

        // Si no se encuentra nada
        return null;
    }

    private static function normalizeHostname(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
            $value = 'https://' . $value;
        }

        $host = parse_url($value, PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return null;
        }

        return strtolower($host);
    }

    public static function parseDateOrNull($value, $formatIn = 'd-m-Y', $formatOut = 'Y-m-d') {
        return !empty($value) && Carbon::hasFormat($value, $formatIn)
            ? Carbon::createFromFormat($formatIn, $value)->format($formatOut)
            : null;
    }

    public static function generatePaginationData($data): array
    {
        return [
            'links' => $data->links(), // normalmente HTML
            'meta' => [
                'current_page' => $data->currentPage(),
                'from'         => $data->firstItem(),
                'last_page'    => $data->lastPage(),
                'pages_urls'   => $data->getUrlRange(1, $data->lastPage()),
                'path'         => $data->path(),
                'per_page'     => $data->perPage(),
                'to'           => $data->lastItem(),
                'total'        => $data->total(),
            ],
        ];
    }
}

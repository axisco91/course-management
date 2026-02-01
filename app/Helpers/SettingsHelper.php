<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    /**
     * Obtener UN setting
     */
    public static function getSetting(string $key, int $companyId)
    {
        return DB::table('setting_definitions as s')
            ->leftJoin('company_setting_overrides as o', function ($join) use ($companyId) {
                $join->on('o.setting_id', '=', 's.id')
                    ->where('o.main_company_id', $companyId);
            })
            ->where('s.key', $key)
            ->selectRaw('COALESCE(o.value, s.value) as value')
            ->value('value');
    }

    /**
     * Obtener TODOS los settings de una empresa
     */
    public static function getAllSettings(int $companyId): array
    {
        return Cache::remember(
            "settings_all_{$companyId}",
            300, // 5 min
            function () use ($companyId) {

                $rows = DB::table('setting_definitions as s')
                    ->leftJoin('company_setting_overrides as o', function ($join) use ($companyId) {
                        $join->on('o.setting_id', '=', 's.id')
                            ->where('o.main_company_id', $companyId);
                    })
                    ->select(
                        's.key',
                        DB::raw('COALESCE(o.value, s.value) as value')
                    )
                    ->get();

                // Convertir a array key => value
                return $rows->pluck('value', 'key')->toArray();
            }
        );
    }
}

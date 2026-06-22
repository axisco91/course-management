<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use Illuminate\Http\Request;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Auth;

class CompanySettingController extends BaseController
{
    /**
     * Obtener TODOS los settings de una empresa
     * GET /companies/settings
     */
    public function index(Request $request)
    {
        $companyId = $this->resolveCompanyId($request);

        if ($companyId === null) {
            return $this->sendError('No se pudo resolver la empresa de la peticion', [], 400);
        }

        $settings = SettingsHelper::getAllSettings($companyId);

        return $this->sendResponse(
            [
                'company_settings' => $settings,
            ],
            trans('Creado con éxito')
        );
    }

    /**
     * Obtener UN setting por key
     * GET /companies/settings/{key}
     */
    public function show($key, Request $request)
    {
        $companyId = $this->resolveCompanyId($request);

        if ($companyId === null) {
            return $this->sendError('No se pudo resolver la empresa de la peticion', [], 400);
        }

        $value = SettingsHelper::getSetting($key, $companyId);

        if ($value === null) {
            return response()->json([
                'success' => false,
                'message' => 'Setting no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'key' => $key,
            'value' => $value
        ]);
    }

    /**
     * Obtener VARIOS settings por keys
     * POST /companies/settings/bulk
     * body: { "keys": ["cursos.automaticos", "emails.activos"] }
     */
    public function bulk(Request $request)
    {
        $companyId = $this->resolveCompanyId($request);

        if ($companyId === null) {
            return $this->sendError('No se pudo resolver la empresa de la peticion', [], 400);
        }

        $keys = $request->input('keys', []);

        $allSettings = SettingsHelper::getAllSettings($companyId);

        $filtered = collect($allSettings)
            ->only($keys);

        return response()->json([
            'success' => true,
            'data' => $filtered
        ]);
    }

    private function resolveCompanyId(Request $request): ?int
    {
        $companyUrl = $request->headers->get('origin')
            ?? $request->headers->get('referer')
            ?? $request->getHost();

        return GeneralHelpers::urlObtainCompanyId(
            $companyUrl,
            Auth::id()
        );
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Services\EmailTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\WebPlatform;

class EmailTemplateController extends BaseController
{
    public function index(Request $request, EmailTemplateService $service)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $webPlatformId = (int) $request->input('web_platform_id') ?: null;
        if ($webPlatformId) {
            WebPlatform::where('id', $webPlatformId)->where('main_company_id', $mainCompanyId)->firstOrFail();
        }

        return $this->sendResponse([
            'email_templates' => $service->catalog($mainCompanyId, $webPlatformId),
        ], trans('Obtenido con éxito'));
    }

    public function update(string $type, Request $request, EmailTemplateService $service)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        if (!$mainCompanyId) {
            return response()->json(['message' => 'No se ha podido identificar la empresa.'], 400);
        }

        $request->validate([
            'mail_type' => ['required', Rule::in(EmailTemplateService::TYPES)],
            'subject' => ['required', 'string', 'max:255'],
            'body_html' => ['required', 'string', 'max:100000'],
            'web_platform_id' => ['nullable', 'integer', 'exists:web_platforms,id'],
        ]);

        if ($request->mail_type !== $type) {
            return response()->json(['message' => 'El tipo de plantilla no coincide.'], 422);
        }

        if ($request->filled('web_platform_id')) {
            WebPlatform::where('id', $request->web_platform_id)->where('main_company_id', $mainCompanyId)->firstOrFail();
        }

        if ($request->filled('web_platform_id')) {
            $service->saveForPlatform((int) $request->web_platform_id, $type, $request->subject, $request->body_html);
        } else {
            $service->save($mainCompanyId, $type, $request->subject, $request->body_html);
        }

        return $this->sendResponse([], 'Plantilla guardada correctamente.');
    }

    public function destroy(string $type, Request $request, EmailTemplateService $service)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        if (!$mainCompanyId) {
            return response()->json(['message' => 'No se ha podido identificar la empresa.'], 400);
        }

        if ($request->filled('web_platform_id')) {
            WebPlatform::where('id', $request->web_platform_id)->where('main_company_id', $mainCompanyId)->firstOrFail();
        }

        if ($request->filled('web_platform_id')) {
            $service->resetForPlatform((int) $request->web_platform_id, $type);
        } else {
            $service->reset($mainCompanyId, $type);
        }

        return $this->sendResponse([], 'Plantilla original restaurada.');
    }
}

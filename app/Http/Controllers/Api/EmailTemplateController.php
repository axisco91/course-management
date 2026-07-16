<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Services\EmailTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmailTemplateController extends BaseController
{
    public function index(Request $request, EmailTemplateService $service)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return $this->sendResponse([
            'email_templates' => $service->catalog($mainCompanyId),
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
        ]);

        if ($request->mail_type !== $type) {
            return response()->json(['message' => 'El tipo de plantilla no coincide.'], 422);
        }

        $service->save($mainCompanyId, $type, $request->subject, $request->body_html);

        return $this->sendResponse([], 'Plantilla guardada correctamente.');
    }

    public function destroy(string $type, Request $request, EmailTemplateService $service)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        if (!$mainCompanyId) {
            return response()->json(['message' => 'No se ha podido identificar la empresa.'], 400);
        }

        $service->reset($mainCompanyId, $type);

        return $this->sendResponse([], 'Plantilla original restaurada.');
    }
}

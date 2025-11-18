<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentTypeController extends BaseController
{

    /**
     * Obtenemos los documentos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $documentTypes = DocumentType::getTypes($mainCompanyId);
            return $documentTypes;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Models\DocumentType;

class DocumentTypeController extends BaseController
{

    /**
     * Obtenemos los documentos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        try {
            $documentTypes = DocumentType::getTypes();
            return $documentTypes;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

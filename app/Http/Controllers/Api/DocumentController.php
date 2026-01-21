<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Requests\DocumentRequests;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\DocumentStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends BaseController
{
    /**
     * Obtenemos los documentos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = Document::select('documents.*')
                ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
                ->FilterMainCompanyId($mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $documents = DocumentResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'documents' => $documents,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $documents = DocumentResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'documents' => $documents,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenemos los documentos de los alumnos
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentDocuments(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return $this->sendResponse(
                [
                    'student_documents' => Document::where('document_type_id', $request->document_type_id)
                        ->FilterMainCompanyId($mainCompanyId)
                        ->get(),
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Creamos los documentos
     * @param DocumentRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DocumentRequests $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $document = Document::createWithService($data);

            $document = Document::where('id', $document->id)
                ->first();

            return $this->sendResponse(
                [
                    'document' => $document,
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id, DocumentRequests $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $document = Document::where('id', $id)
                ->FilterMainCompanyId($mainCompanyId)
                ->first();

            if ($document) {
                $document = $document->updateWithService($document, $data);

                return $this->sendResponse(
                    [
                        'document' => $document,
                    ],
                    trans('Obtenido con éxito')
                );
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenemos el documento
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id,Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $document = Document::select('documents.*', 'document_types.name as document_type')
            ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
            ->where('documents.id', $id)
            ->FilterMainCompanyId($mainCompanyId)
            ->first();

        if ($document) {
            return $this->sendResponse(
                [
                    'document' => $document,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
                'message' => 'Documento no existe'
        ]);
    }

    /**
     * Eliminar documento
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $document = Document::where('id', $id)
                    ->FilterMainCompanyId($mainCompanyId)
                    ->first();

                if (!$document) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Documento no existe'
                    ]);
                }

                Document::destroy($id);
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

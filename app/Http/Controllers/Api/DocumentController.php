<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\DocumentRequests;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentStudent;
use App\Models\TrainingAction;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends BaseController
{
    private $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Obtenemos los documentos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            $documents = Document::select('documents.*')
                ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
                ->get();
            return $documents;
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
            return Document::where('document_type_id', $request->document_type_id)->get();
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
            $data = $request->all();
            $document = $this->documentService->create($data);
            $document = Document::where('id', $document->id)
                ->first();
            $documentUser = DocumentStudent::where('document_id', $document->id)->first();
            if ($documentUser) {
                $document['used'] = true;
            } else {
                $document['used'] = false;
            }
          return response()->json([
              'status' => 200,
              'document' => $document
          ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id, DocumentRequests $request){
        try {
            $data = $request->all();
            $document = Document::find($id);
            if ($document) {
                $document = $this->documentService->update($document, $data);
                $document = Document::where('id', $document->id)
                    ->first();
                $documentUser = DocumentStudent::where('document_id', $document->id)->first();
                if ($documentUser) {
                    $document['used'] = true;
                } else {
                    $document['used'] = false;
                }
                return response()->json([
                    'status' => 200,
                    'document' => $document
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenemos la acción formativa
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $document = Document::select('documents.*', 'document_types.name as document_type')
            ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
            ->where('documents.id', $id)
            ->first();

        if ($document) {
            return response()->json([
                'status' => 200,
                'document' => $document
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Acción Formativa no existe'
        ]);
    }

    /**
     * Eliminar acciones formativas
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                TrainingAction::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

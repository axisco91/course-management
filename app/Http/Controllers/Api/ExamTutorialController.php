<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ExamTutorialResource;
use App\Models\ExamTutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamTutorialController extends BaseController
{

    public function getExamTutorial($id, Request $request) {
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $query =  ExamTutorial::getExamTutorials($id, $mainCompanyId);

                if ($request->filled('perPage')) {
                    $perPage = (int) $request->perPage;

                    $paginator = $query->paginate($perPage);

                    // Resource sobre el paginator
                    $examTutorial = ExamTutorialResource::collection($paginator);
                    // Si no tienes Resource, podrías usar directamente:
                    // $certifications = $paginator->items();

                    // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                    $paginationData = GeneralHelpers::generatePaginationData($paginator);

                    return $this->sendResponse(
                        [
                            'exam_tutorial' => $examTutorial,
                            'links'          => $paginationData['links'],
                            'meta'           => $paginationData['meta'],
                        ],
                        trans('Obtenido con éxito')
                    );
                }

                // SIN PAGINACIÓN
                $examTutorial = ExamTutorialResource::collection($query->get());
                // o, sin resource: $certifications = $query->get();

                return $this->sendResponse(
                    [
                        'exam_tutorial' => $examTutorial,
                    ],
                    trans('Obtenido con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $examTutorial = ExamTutorial::createWithService($data);

            return $this->sendResponse(
                [
                    'exam_tutorial' => ExamTutorial::getExamTutorial($examTutorial->id, $mainCompanyId)->first(),
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

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $examTutorial = ExamTutorial::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$examTutorial) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Examen o Tutoría no encontrado'
                ]);
            }

            $examTutorial->updateWithService($id, $request);

            return $this->sendResponse(
                [
                    'exam_tutorial' => ExamTutorial::getExamTutorial($examTutorial->id, $mainCompanyId)->first(),
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $examTutorial = ExamTutorial::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$examTutorial) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Examen o Tutoría no encontrado'
                    ]);
                }

                ExamTutorial::destroy($id);
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

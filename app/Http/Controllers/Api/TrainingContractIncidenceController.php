<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingContractIncidenceResource;
use App\Models\TrainingContractIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingContractIncidenceController extends BaseController
{
    public function trainingContractIncidences($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = TrainingContractIncidence::getTrainingContractIncidence($id, $mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $trainingContractIncidences = TrainingContractIncidenceResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_contract_incidences' => $trainingContractIncidences,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $trainingContractIncidences = TrainingContractIncidenceResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'training_contract_incidences' => $trainingContractIncidences,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $incidence = TrainingContractIncidence::createWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_contract_incidence' => TrainingContractIncidence::getTrainingContractIncidence($request->training_contract_id, $mainCompanyId)->where('training_contract_incidences.id', $incidence->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $incidence = TrainingContractIncidence::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$incidence){
                return response()->json([
                    'status' => 404,
                    'message' => 'Incidencia no existe'
                ]);
            }

            $incidence->updateWithService($request);

            return $this->sendResponse(
                [
                    'training_contract_incidence' => TrainingContractIncidence::getTrainingContractIncidence($mainCompanyId)->where('training_contract_incidences.id', $incidence->id)->first(),
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

    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());


        $incidence = TrainingContractIncidence::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if (!$incidence){
            return response()->json([
                'status' => 404,
                'message' => 'Incidencia no existe'
            ]);
        }
        if ($incidence) {
            return $this->sendResponse(
                [
                    'training_contract_incidence' => $incidence,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'incidences no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $incidence = TrainingContractIncidence::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$incidence){
                    return response()->json([
                        'status' => 404,
                        'message' => 'Incidencia no existe'
                    ]);
                }

                TrainingContractIncidence::destroy($id);
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

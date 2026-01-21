<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingContractStatusResource;
use App\Models\TrainingContractStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractStatusController extends BaseController
{
    public function getTrainingContractStatuses(Request $request) {
        try {
            $query = TrainingContractStatus::getTrainingContractStatus();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $trainingContractStatuses = TrainingContractStatusResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_contract_statuses' => $trainingContractStatuses,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $trainingContractStatuses = TrainingContractStatusResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'training_contract_statuses' => $trainingContractStatuses,
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
            $status = TrainingContractStatus::createTrainingContractStatus($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_contract_status' => TrainingContractStatus::getTrainingContractStatus()->where('training_contract_statuses.id', $status->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $status = TrainingContractStatus::updateTrainingContractStatus($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_contract_status' => TrainingContractStatus::getTrainingContractStatus()->where('training_contract_statuses.id', $status->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function getTrainingContractStatus($id){
        $status = TrainingContractStatus::getTrainingContractStatus()->where('training_contract_statuses.id', $id)->first();
        if ($status) {
            return $this->sendResponse(
                [
                    'training_contract_status' => $status,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Estado no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingContractStatus::destroy($id);
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

    public function count() {
        return TrainingContractStatus::count();
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingUnitResource;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingUnitController extends BaseController
{
    public function trainingUnits(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = TrainingUnit::getTrainingUnits($mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $trainingUnits = TrainingUnitResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_units' => $trainingUnits,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $trainingUnits = TrainingUnitResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'training_units' => $trainingUnits,
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

            $trainingUnit = TrainingUnit::creatWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_unit' => TrainingUnit::getTrainingUnit($trainingUnit->id, $mainCompanyId)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $trainingUnit = TrainingUnit::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$trainingUnit) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Unidad no encontrada'
                ]);
            }

            $trainingUnit->updateWithService($request);

            return $this->sendResponse(
                [
                    'training_unit' => TrainingUnit::getTrainingUnit($trainingUnit->id, $mainCompanyId)->first(),
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

    public function getTrainingUnit($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $trainingUnit = TrainingUnit::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if (!$trainingUnit) {
            return response()->json([
                'status' => 404,
                'message' => 'Unidad no encontrada'
            ]);
        }

        if ($trainingUnit) {
            return $this->sendResponse(
                [
                    'training_unit' => $trainingUnit,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Unidad formativa no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $trainingUnit = TrainingUnit::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$trainingUnit) {
                    return $this->sendResponse(
                        [],
                        trans('Eliminado con éxito')
                    );
                }

                TrainingUnit::destroy($id);
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

    public function count(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        return TrainingUnit::FilterMainCompany($mainCompanyId)->count();
    }
}

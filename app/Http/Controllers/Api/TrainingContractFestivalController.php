<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingContractFestivalResource;
use App\Models\TrainingContract;
use App\Models\TrainingContractFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TrainingContractFestivalController extends BaseController
{

    public function getTrainingContractFestivals(Request $request) {
        if ($request->has('id')) {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            try {
                $query = TrainingContractFestival::festivals($mainCompanyId)->where('training_contract_id', $request->id)->orderBy('day');

                if ($request->filled('perPage')) {
                    $perPage = (int) $request->perPage;

                    $paginator = $query->paginate($perPage);

                    // Resource sobre el paginator
                    $trainingContractFestivals = TrainingContractFestivalResource::collection($paginator);
                    // Si no tienes Resource, podrías usar directamente:
                    // $certifications = $paginator->items();

                    // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                    $paginationData = GeneralHelpers::generatePaginationData($paginator);

                    return $this->sendResponse(
                        [
                            'training_contract_festivals' => $trainingContractFestivals,
                            'links'          => $paginationData['links'],
                            'meta'           => $paginationData['meta'],
                        ],
                        trans('Obtenido con éxito')
                    );
                }

                // SIN PAGINACIÓN
                $trainingContractFestivals = TrainingContractFestivalResource::collection($query->get());
                // o, sin resource: $certifications = $query->get();

                return $this->sendResponse(
                    [
                        'training_contract_festivals' => $trainingContractFestivals,
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
            $trainingContract = TrainingContract::where('id', $request['training_contract_id'])->first();
            TrainingContractFestival::createTrainingContractFestivals($request['training_contract_id'], $request['id'], $request['type'], $trainingContract->beginning_formation, $trainingContract->end_formation, $mainCompanyId);


            return $this->sendResponse(
                [
                    'training_contract_festivals' => TrainingContractFestival::festivals($mainCompanyId)->where('training_contract_id', $trainingContract->id)->get(),
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $festival = TrainingContractFestival::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$festival) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Festivo no encontrado'
                    ]);
                }

                TrainingContractFestival::destroy($id);
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

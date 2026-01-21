<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingContractExcludedDayResource;
use App\Models\TrainingContract;
use App\Models\TrainingContractsExcludedDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingContractExcludedDayController extends BaseController
{

    public function getTrainingContractExcludedDays(Request $request) {
        if ($request->has('id')) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $query = TrainingContractsExcludedDay::sameGroup($request->id, $mainCompanyId);

                if ($request->filled('perPage')) {
                    $perPage = (int) $request->perPage;

                    $paginator = $query->paginate($perPage);

                    // Resource sobre el paginator
                    $trainingContractExcludedDays = TrainingContractExcludedDayResource::collection($paginator);
                    // Si no tienes Resource, podrías usar directamente:
                    // $certifications = $paginator->items();

                    // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                    $paginationData = GeneralHelpers::generatePaginationData($paginator);

                    return $this->sendResponse(
                        [
                            'training_contract_excluded_days' => $trainingContractExcludedDays,
                            'links'          => $paginationData['links'],
                            'meta'           => $paginationData['meta'],
                        ],
                        trans('Obtenido con éxito')
                    );
                }

                // SIN PAGINACIÓN
                $trainingContractExcludedDays = TrainingContractExcludedDayResource::collection($query->get());
                // o, sin resource: $certifications = $query->get();

                return $this->sendResponse(
                    [
                        'training_contract_excluded_days' => $trainingContractExcludedDays,
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
        if ($request['beginning'] && $request['end'] && $request['excluded_day_type_id']) {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;
            TrainingContractsExcludedDay::createExcludedDay($data);

            return $this->sendResponse(
                [
                    'training_contract_excluded_days' => TrainingContractsExcludedDay::sameGroup($request->training_contract_id, $mainCompanyId)
                        ->get(),
                ],
                trans('Creado con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'error' => 'No has introducido uan fecha'
        ]);
    }

    public function createGroup(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $trainingContract = TrainingContract::find($request['training_contract_id']);
        if ($request['type'] === 'general') {
            TrainingContractsExcludedDay::addGeneralDays($request['training_contract_id'], $trainingContract->beginning, $trainingContract->end);
        } else {
            TrainingContractsExcludedDay::createTrainingContractExcludedDay($request['training_contract_id'], $request['id'], $request['type'], $trainingContract->beginning, $trainingContract->end, $mainCompanyId);
        }

        return $this->sendResponse(
            [
                'training_contract_excluded_days' => TrainingContractsExcludedDay::select('training_contracts_excluded_days.*', 'excluded_days.day')
                    ->leftJoin('excluded_days', 'excluded_days.id', '=', 'training_contracts_excluded_days.excluded_day_id')
                    ->where('training_contract_id', $request['training_contract_id'])->get()
            ],
            trans('creado con éxito')
        );
    }

    public function destroy($group, Request $request){
        if ($group) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $excludedDays = TrainingContractsExcludedDay::where('group', $group)
                    ->FilterMainCompany($mainCompanyId)
                    ->get();
                foreach ($excludedDays as $excludedDay) {
                    TrainingContractsExcludedDay::destroy($excludedDay->id);
                }
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

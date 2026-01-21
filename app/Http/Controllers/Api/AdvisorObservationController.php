<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\AdvisorObservationResource;
use App\Models\AdvisorObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvisorObservationController extends BaseController
{

    public function index($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $params =  AdvisorObservation::ForAdvisor($id, $mainCompanyId);

            if ($request->perPage) {
                $advisorObservations = AdvisorObservationResource::collection($params->paginate(intval(request('perPage'))));
                $paginationData = GeneralHelpers::generatePaginationData($params);
                return $this->sendResponse(
                    [
                        'advisor_observations' => $advisorObservations,
                        'links'       => $paginationData['links'],
                        'meta'        => $paginationData['meta'],
                    ],
                    trans('Obtenido')
                );
            }
            $advisorObservations = AdvisorObservationResource::collection($params->get());

            return $this->sendResponse(
                [
                    'advisor_observations' => $advisorObservations,
                ],
                trans('Obtenido')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request){
        try {
            $data = $request->all();
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data['main_company_id'] = $mainCompanyId;

            $observation = AdvisorObservation::createWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'advisor_observation' => $observation,
            ],
            trans('Creado con éxito')
        );
    }

    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $advisorObservation = AdvisorObservation::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$advisorObservation) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Observación no encontrada'
                ]);
            }
            $data = $request->all();
            $advisorObservation->updateWithService($data);

        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'advisor_observation' => $advisorObservation,
            ],
            trans('Actualizado con éxito')
        );
    }

    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $observation = AdvisorObservation::where('advisor_observations.id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if ($observation) {
            return $this->sendResponse(
                [
                    'advisor_observation' => $observation,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 404,
            'message' => 'observación no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                AdvisorObservation::destroy($id);
                return $this->sendResponse(
                    [

                    ],
                    trans('Elimiando con éxito')
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

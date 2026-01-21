<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CompanyObservationResource;
use App\Models\CompanyObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyObservationController extends BaseController
{
    public function companyObservations($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = CompanyObservation::getCompanyObservations($id, $mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $companyObservations = CompanyObservationResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'company_observations' => $companyObservations,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $companyObservations = CompanyObservationResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'company_observations' => $companyObservations,
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

            $observation = CompanyObservation::createWithService($data);

            return $this->sendResponse(
                [
                    'company_observation' => $observation,
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

            $companyObservation = CompanyObservation::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$companyObservation) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Observación no encontrada'
                ]);
            }
            $data = $request->all();

            $companyObservation->updateWithService($data);

            return $this->sendResponse(
                [
                    'company_observation' => $companyObservation,
                ],
                trans('Actualizado con éxito')
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

        $observation = CompanyObservation::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if ($observation) {
            return $this->sendResponse(
                [
                    'company_observation' => $observation,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'observación no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $observation = CompanyObservation::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$observation) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Observación no existe'
                    ]);
                }

                CompanyObservation::destroy($id);
                return $this->sendResponse(
                    [

                    ],
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

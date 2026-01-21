<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CompanyActivityResource;
use App\Models\CompanyActivity;
use Illuminate\Http\Request;

class CompanyActivityController extends BaseController
{
    public function companyActivities(Request $request) {
        try {
            $query = CompanyActivity::select('*');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $companyActivity = CompanyActivityResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'company_activities' => $companyActivity,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $companyActivity = CompanyActivityResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'company_activities' => $companyActivity,
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
            CompanyActivity::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $companyActivity = CompanyActivity::find($id);
            $companyActivity->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [],
            trans('Guardado con éxito')
        );
    }

    public function getCompanyActivity($id){
        $companyActivity = CompanyActivity::find($id);
        if ($companyActivity) {
            return $this->sendResponse(
                [
                    'company_activity' => $companyActivity,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Actividad no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CompanyActivity::destroy($id);
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

    public function count(){
        return CompanyActivity::count();
    }
}

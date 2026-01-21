<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CompanyTypeResource;
use App\Models\CompanyType;
use Illuminate\Http\Request;

class CompanyTypeController extends BaseController
{
    public function companyTypes(Request $request) {
        try {
            $query = CompanyType::getCompanyTypes();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $company_types = CompanyTypeResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'company_types' => $company_types,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $company_types = CompanyTypeResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'company_types' => $company_types,
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
            $companyType = CompanyType::createCompanyType($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'company_type' => $companyType,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $companyType = CompanyType::updateCompanyType($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'company_type' => $companyType,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getCompanyType($id){
        $companyType = CompanyType::find($id);
        if ($companyType) {
            return $this->sendResponse(
                [
                    'company_type' => $companyType,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CompanyType::destroy($id);
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

    public function count(){
        return CompanyType::count();
    }
}

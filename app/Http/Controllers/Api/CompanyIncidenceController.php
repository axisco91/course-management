<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CompanyIncidencceResource;
use App\Models\CompanyIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyIncidenceController extends BaseController
{
    public function companyIncidences($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = CompanyIncidence::getCompanyIncidences($id, $mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $companyIncidences = CompanyIncidencceResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'company_incidences' => $companyIncidences,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $companyIncidences = CompanyIncidencceResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'company_incidences' => $companyIncidences,
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

            $incidence = CompanyIncidence::createWithService($data);

            return $this->sendResponse(
                [
                    'company_incidence' => CompanyIncidence::getCompanyIncidence($incidence->id, $mainCompanyId)->first(),
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

            $incidence = CompanyIncidence::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if (!$incidence) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Incidencia no encontrada'
                ]);
            }

            $data = $request->all();

            $incidence = $incidence->updateWithService($data);

            return $this->sendResponse(
                [
                    'company_incidence' => CompanyIncidence::getCompanyIncidence($incidence->id, $mainCompanyId)->first(),
                ],
                trans('Obtenido con éxito')
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

        $incidence = CompanyIncidence::getCompanyIncidence($id, $mainCompanyId)->first();
        if ($incidence) {
            return $this->sendResponse(
                [
                    'company_incidence' => $incidence,
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

                $incidence = CompanyIncidence::where('id', $id)
                    ->FilterMainCompanyId($mainCompanyId)
                    ->first();
                if (!$incidence) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Incidencia no encontrada'
                    ]);
                }

                CompanyIncidence::destroy($id);
                return $this->sendResponse([

                ], trans('eliminado con éxito'));
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

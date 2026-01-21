<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\IncidenceTypeResource;
use App\Models\IncidenceType;
use Illuminate\Http\Request;

class IncidenceTypeController extends BaseController
{
    public function getIncidenceTypes(Request $request) {
        try {
            $query = IncidenceType::getIncidenceType();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $incidenceTypes = IncidenceTypeResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'incidence_types' => $incidenceTypes,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $incidenceTypes = IncidenceTypeResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'incidence_types' => $incidenceTypes,
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
            $type = IncidenceType::createWithService($request->all());

            $type = IncidenceType::getIncidenceType()->where('incidence_types.id', $type->id)->first();
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'incidence_type' => $type,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $type = IncidenceType::find($id);

            $type->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'incidence_type' => $type,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getIncidenceType($id){
        $type = IncidenceType::getIncidenceType()->where('incidence_types.id', $id);
        if ($type) {
            return $this->sendResponse(
                [
                    'incidence_type' => $type,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Centro no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                IncidenceType::destroy($id);
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

    public function count(){
        return IncidenceType::count();
    }
}

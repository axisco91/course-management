<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CnaeResource;
use App\Models\Cnae;
use Illuminate\Http\Request;

class CnaeController extends BaseController
{
    public function cnaes(Request $request) {
        try {
            $query = Cnae::select('*');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $cnaes = CnaeResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'cnaes' => $cnaes,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $cnaes = CnaeResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'cnaes' => $cnaes,
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
            $cnae = Cnae::createCnae($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'cnae' => $cnae,
            ],
            trans('Guardado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $cnae = Cnae::updateCnae($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'cnae' => $cnae,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getCnae($id){
        $cnae = Cnae::where('id', $id)->first();
        if ($cnae) {
            return $this->sendResponse(
                [
                    'cnae' => $cnae,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Cnae no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Cnae::destroy($id);
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
        return Cnae::count();
    }
}

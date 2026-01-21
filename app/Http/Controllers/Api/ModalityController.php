<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ModalityResource;
use App\Models\Modality;
use Illuminate\Http\Request;

class ModalityController extends BaseController
{
    public function modalities(Request $request) {
        try {
            $query = Modality::getModality();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $modalities = ModalityResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'modalities' => $modalities,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $modalities = ModalityResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'modalities' => $modalities,
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
            $modality = Modality::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'modality' => Modality::getModality()->where('modalities.id', $modality->id)->first(),
            ],
            trans('creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $modality = Modality::find($id);
            $modality->updateWithService($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'modality' => Modality::getModality()->where('modalities.id', $modality->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function getModality($id){
        $modality = Modality::getModality()->where('modalities.id', $id)->first();
        if ($modality) {
            return $this->sendResponse(
                [
                    'modality' => $modality,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Modalidad no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Modality::destroy($id);
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
        return Modality::count();
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ModalityResource;
use App\Models\Modality;
use Illuminate\Http\Request;

class ModalityController extends BaseController
{
    public function modalities(Request $request)
    {
        try {
            $query = Modality::getModality();

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=online
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ SORT (por defecto name asc)
            // ?sort=name   -> asc
            // ?sort=-name  -> desc
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // (opcional) whitelist de columnas ordenables
            $allowedSorts = ['id', 'name', 'created_at', 'updated_at'];
            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $modalities = ModalityResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'modalities' => $modalities,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $modalities = ModalityResource::collection($query->get());

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
                'modality' => $modality,
            ],
            trans('creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $modality = Modality::find($id);
            $modality->updateWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'modality' => $modality,
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

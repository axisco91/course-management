<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProfessionalAreaResource;
use App\Models\ProfessionalArea;
use Illuminate\Http\Request;

class ProfessionalAreaController extends BaseController
{
    public function professionalAreas(Request $request)
    {
        try {
            $query = ProfessionalArea::getProfessionalArea();

            // ✅ FILTRO POR NOMBRE (search)
            // Ej: ?search=gestion
            if ($request->filled('name')) {
                $search = trim($request->input('name'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ SORT (por defecto name asc)
            // Ej:
            //   ?sort=name      -> asc
            //   ?sort=-name     -> desc
            //   ?sort=created_at / -created_at -> si lo permites
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // (opcional) whitelist de columnas ordenables
            $allowedSorts = ['name', 'id', 'created_at', 'updated_at'];
            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $professionalAreas = ProfessionalAreaResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'professional_areas' => $professionalAreas,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $professionalAreas = ProfessionalAreaResource::collection($query->get());

            return $this->sendResponse(
                [
                    'professional_areas' => $professionalAreas,
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
            $area = ProfessionalArea::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_area' => $area,
            ],
            trans('Created con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $area = ProfessionalArea::find($id);
            $area->updateWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'professional_area' => $area,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function getProfessionalArea($id){
        $area = ProfessionalArea::getProfessionalArea()->where('professional_areas.id', $id)->first();
        if ($area) {
            return $this->sendResponse(
                [
                    'professional_area' => $area,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Area no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProfessionalArea::destroy($id);
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
        return ProfessionalArea::count();
    }
}

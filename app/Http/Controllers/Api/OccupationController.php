<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\OccupationResource;
use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends BaseController
{
    public function getOccupations(Request $request)
    {
        try {
            $query = Occupation::getOccupation();

            // ✅ FILTRO POR NOMBRE
            // ?search=texto
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ FILTRO POR CNO
            // ?cno=123
            if ($request->filled('cno')) {
                $query->where('cno', 'LIKE', '%' . trim($request->input('cno')) . '%');
            }

            // ✅ SORT
            // ?sort=name   -> asc
            // ?sort=-name  -> desc
            // ?sort=cno    -> asc
            // ?sort=-cno   -> desc
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // 🔒 columnas permitidas
            $allowedSorts = ['id', 'name', 'cno', 'created_at', 'updated_at'];

            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $occupations = OccupationResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'occupations' => $occupations,
                        'links'       => $paginationData['links'],
                        'meta'        => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $occupations = OccupationResource::collection($query->get());

            return $this->sendResponse(
                [
                    'occupations' => $occupations,
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
            $occupation = Occupation::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'occupation' => $occupation,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $occupation = Occupation::find($id);
            $occupation->updateWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'occupation' => Occupation::getOccupation()->where('occupations.id', $occupation->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function getOccupation($id){
        $occupation = Occupation::getOccupation()->where('occupations.id', $id)->first();
        if ($occupation) {
            return $this->sendResponse(
                [
                    'occupation' => $occupation,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Ocupación no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Occupation::destroy($id);
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
        return Occupation::count();
    }
}

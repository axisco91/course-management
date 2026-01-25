<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingActionGroupResource;
use App\Models\TrainingActionGroup;
use Illuminate\Http\Request;

class TrainingActionGroupController extends BaseController
{
    public function trainingActionGroups(Request $request)
    {
        try {
            $query = TrainingActionGroup::getTrainingActionGroup();

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=grupo
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

            // (opcional) whitelist
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

                $trainingActionGroups = TrainingActionGroupResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_action_groups' => $trainingActionGroups,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $trainingActionGroups = TrainingActionGroupResource::collection($query->get());

            return $this->sendResponse(
                [
                    'training_action_groups' => $trainingActionGroups,
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
            $group = TrainingActionGroup::createTrainingActionGroup($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_action_group' => $group,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $group = TrainingActionGroup::updateTrainingActionGroup($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_action_group' => $group,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getTrainingActionGroup($id){
        $group = TrainingActionGroup::getTrainingActionGroup()->where('training_action_groups.id', $id)->first();
        if ($group) {
            return $this->sendResponse(
                [
                    'training_action_group' => $group,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Grupo no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingActionGroup::destroy($id);
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
        return TrainingActionGroup::count();
    }
}

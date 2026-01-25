<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingActionLevelResource;
use App\Models\TrainingActionLevel;
use Illuminate\Http\Request;

class TrainingActionLevelController extends BaseController
{
    public function getTrainingActionLevels(Request $request)
    {
        try {
            $query = TrainingActionLevel::getTrainingActionLevel();

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=basico
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

                // ✅ Resource correcto (antes tenías TrainingActionGroupResource)
                $trainingActionLevels = TrainingActionLevelResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_action_levels' => $trainingActionLevels,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $trainingActionLevels = TrainingActionLevelResource::collection($query->get());

            return $this->sendResponse(
                [
                    'training_action_levels' => $trainingActionLevels,
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
            $level = TrainingActionLevel::createTrainingActionLevel($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_action_level' => TrainingActionLevel::getTrainingActionLevel()->where('training_action_levels.id', $level->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $level = TrainingActionLevel::updateTrainingActionLevel($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'training_action_level' => TrainingActionLevel::getTrainingActionLevel()->where('training_action_levels.id', $level->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function getTrainingActionLevel($id){
        $level = TrainingActionLevel::getTrainingActionLevel()->where('training_action_levels.id', $id)->first();
        if ($level) {
            return $this->sendResponse(
                [
                    'training_action_level' => $level,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Nivel no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingActionLevel::destroy($id);
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
        return TrainingActionLevel::count();
    }
}

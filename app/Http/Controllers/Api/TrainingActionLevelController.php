<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingActionGroupResource;
use App\Http\Resources\TrainingActionLevelResource;
use App\Models\TrainingActionLevel;
use Illuminate\Http\Request;

class TrainingActionLevelController extends BaseController
{
    public function getTrainingActionLevels(Request $request) {
        try {
            $query = TrainingActionLevel::getTrainingActionLevel();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $trainingActionLevels = TrainingActionGroupResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_action_levels' => $trainingActionLevels,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $trainingActionLevels = TrainingActionLevelResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

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

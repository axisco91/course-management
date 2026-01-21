<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingActionGroupResource;
use App\Models\TrainingActionGroup;
use Illuminate\Http\Request;

class TrainingActionGroupController extends BaseController
{
    public function trainingActionGroups(Request $request) {
        try {
            $query = TrainingActionGroup::getTrainingActionGroup();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $trainingActionGroups = TrainingActionGroupResource::collection($paginator);

                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'cnaes' => $trainingActionGroups,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $trainingActionGroups = TrainingActionGroupResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

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
                'training_action_group' => TrainingActionGroup::getTrainingActionGroup()->where('training_action_groups.id', $group->id)->first(),
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
                'training_action_group' => TrainingActionGroup::getTrainingActionGroup()->where('training_action_groups.id', $group->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function getTrainingActionGroup($id){
        $group = TrainingActionGroup::getTrainingActionGroup()->where('training_action_groups.id', $id)->first();
        if ($group) {
            return $this->sendResponse(
                [
                    'training_action_group' => $group->id,
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

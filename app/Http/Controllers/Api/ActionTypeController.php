<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\GeneralRequests;
use App\Models\ActionType;
use App\Models\TrainingAction;
use App\Services\ActionTypeService;

class ActionTypeController extends BaseController
{
    private $actionTypeService;

    public function __construct(ActionTypeService $actionTypeService)
    {
        $this->actionTypeService = $actionTypeService;
    }

    /**
     * Obtener tipo acciones
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActionTypes() {
        try {
            $actionTypes = ActionType::getActionType()->get();

            foreach ($actionTypes as $actionType){
                $trainingAction = TrainingAction::where('action_type_id', $actionType['id'])->first();
                if ($trainingAction){
                    $actionType['used'] = true;
                } else{
                    $actionType['used'] = false;
                }
            }

            return $actionTypes;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener tipo acción
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActionType($id){
        $actionType = ActionType::getActionType()
            ->where('id', $id)
            ->first();
        if ($actionType) {
            $training_action = TrainingAction::where('action_type_id', $actionType['id'])->first();
            if ($training_action){
                $actionType['used'] = true;
            } else{
                $actionType['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'action_type' => $actionType
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo Acción no existe'
        ]);
    }

    /**
     * Crear
     * @param GeneralRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(GeneralRequests $request){
        try {
            $data = $request->all();
            $element = $this->actionTypeService->create($data);
            $actionType = ActionType::getActionType()
                ->where('id', $element->id)
                ->first();
            if ($actionType) {
                $training_action = TrainingAction::where('action_type_id', $actionType['id'])->first();
                if ($training_action){
                    $actionType['used'] = true;
                } else{
                    $actionType['used'] = false;
                }
            }
            return response()->json([
                'status' => 200,
                'action_type' => $actionType
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editar
     * @param $id
     * @param GeneralRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, GeneralRequests $request){
        try {
            $actionType = ActionType::find($id);
            $data = $request->all();
            $element = $this->actionTypeService->update($actionType, $data);
            $actionType = ActionType::getActionType()
                ->where('id', $element->id)
                ->first();
            if ($actionType) {
                $training_action = TrainingAction::where('action_type_id', $actionType['id'])->first();
                if ($training_action){
                    $actionType['used'] = true;
                } else{
                    $actionType['used'] = false;
                }
            }
            return response()->json([
                'status' => 200,
                'action_type' => $actionType
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar acción
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                ActionType::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

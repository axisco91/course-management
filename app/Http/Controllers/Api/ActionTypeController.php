<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Requests\GeneralRequests;
use App\Http\Resources\ActionTypeResource;
use App\Models\ActionType;
use Illuminate\Http\Request;

class ActionTypeController extends BaseController
{
    /**
     * Obtener tipo acciones
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActionTypes(Request $request) {
        try {
            $params = ActionType::getActionType()
                ->withExists(['trainingActions as used']);

            if ($request->perPage) {
                $actionTypes = ActionTypeResource::collection($params->paginate(intval(request('perPage'))));
                $paginationData = GeneralHelpers::generatePaginationData($params);
                return $this->sendResponse(
                    [
                        'action_types' => $actionTypes,
                        'links'       => $paginationData['links'],
                        'meta'        => $paginationData['meta'],
                    ],
                    trans('Obtenido')
                );
            }
            $actionTypes = ActionTypeResource::collection($params->get());

            return $this->sendResponse(
                [
                    'action_types' => $actionTypes,
                ],
                trans('Obtenido')
            );
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
        $params = ActionType::getActionType()
            ->withExists(['trainingActions as used'])
            ->where('action_types.id', $id)
            ->first();

        if ($params) {
            $actionType = ActionTypeResource::collection($params);

            return $this->sendResponse(
                [
                    'action_type' => $actionType,
                ],
                trans('Obtenido')
            );
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
            $param = ActionType::createWithService($data);

            $params = ActionType::getActionType()
                ->withExists(['trainingActions as used'])
                ->where('action_types.id', $param->id)
                ->first();

            $actionType = ActionTypeResource::collection($params);

            return $this->sendResponse(
                [
                    'action_type' => $actionType,
                ],
                trans('Creado')
            );
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
            $element = $actionType->updateWithService($data);

            $params = ActionType::getActionType()
                ->withExists(['trainingActions as used'])
                ->where('action_types.id', $element->id)
                ->first();

            $actionType = ActionTypeResource::collection($params);

            return $this->sendResponse(
                [
                    'action_type' => $actionType,
                ],
                trans('Actualizado correctamente')
            );
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
                return $this->sendResponse(
                    [

                    ],
                    trans('Eliminado correctamente')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

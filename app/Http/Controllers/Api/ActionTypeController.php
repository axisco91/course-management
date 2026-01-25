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
    public function getActionTypes(Request $request)
    {
        try {
            $query = ActionType::getActionType();

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=bonificada
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
                $perPage = (int) $request->input('perPage');

                $paginator = $query->paginate($perPage);

                $actionTypes = ActionTypeResource::collection($paginator);

                // ✅ IMPORTANTE: pasar el paginator, no el builder
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'action_types' => $actionTypes,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido')
                );
            }

            // ✅ SIN PAGINACIÓN
            $actionTypes = ActionTypeResource::collection($query->get());

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
            ->where('action_types.id', $id)
            ->first();

        if ($params) {
            $actionType = ActionTypeResource::make($params);

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
            $actionType = ActionType::createWithService($data);

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

            return $this->sendResponse(
                [
                    'action_type' => $element,
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

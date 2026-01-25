<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\OnLeaveResource;
use App\Models\OnLeaveType;
use Illuminate\Http\Request;

class OnLeaveController extends BaseController
{
    public function getOnLeaveTypes(Request $request) {
        try {
            $query = OnLeaveType::getOnLeaveType();

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $onleaves = OnLeaveResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'on_leave_types' => $onleaves,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $onleaves = OnLeaveResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'on_leave_types' => $onleaves,
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
            $on_leave = OnLeaveType::createWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'on_leave' => $on_leave,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $on_leave = OnLeaveType::find($id);
            $on_leave->updateWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'on_leave' => $on_leave,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getOnLeaveType($id){
        $onLeave = OnLeaveType::getOnLeaveType()->where('on_leave_types.id', $id)->first();
        if ($onLeave) {
            return $this->sendResponse(
                [
                    'on_leave' => $onLeave,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo de baja no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                OnLeaveType::destroy($id);
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
        return OnLeaveType::count();
    }
}

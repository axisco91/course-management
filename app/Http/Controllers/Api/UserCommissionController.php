<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Resources\UserCommissionResource;
use App\Models\UserCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCommissionController extends BaseController
{
    /**
     * Obtener comisiones
     * @return mixed
     */
    public function index($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = UserCommission::commissions($mainCompanyId)
                ->where('user_commissions.user_id', $id);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $userCommissions = UserCommissionResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'user_commissions' => $userCommissions,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $userCommissions = UserCommissionResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'user_commissions' => $userCommissions,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    } // end method

    /**
     * Editar comisión
     * @param $id
     * @param Request $request
     */
    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();

            $userCommission = UserCommission::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$userCommission) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Comisión no encontrada'
                ]);
            }

            $userCommission->updateWithService($data);

            return $this->sendResponse(
                [
                    'user_commission' => $userCommission,
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener comisión
     * @param $id
     * @return mixed
     */
    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $userCommission = UserCommission::commissions($mainCompanyId)
            ->where('user_commissions.id', $id)
            ->first();

        if ($userCommission) {
            return $this->sendResponse(
                [
                    'user_commission' => $userCommission,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 404,
            'message' => 'Comisión no existe'
        ]);
    }

    /**
     * Eliminar comisión
     * @param $id
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $userCommission = UserCommission::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$userCommission) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Comisión no existe'
                    ]);
                }

                UserCommission::destroy($id);
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
}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
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

            $commissions = UserCommission::commissions($mainCompanyId)
                ->where('user_commissions.user_id', $id)
                ->get();

            return $commissions;
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

            $userCommission->updateWithService($userCommission, $data);

            $commission = UserCommission::commissions($mainCompanyId)
                ->where('user_commissions.id', $id)
                ->first();

            return response()->json([
                'status' => 200,
                'user_commission' => $commission
            ]);
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
            return response()->json([
                'status' => 200,
                'user_commission' => $userCommission
            ]);
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

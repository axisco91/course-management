<?php

namespace App\Http\Controllers\Api;

use App\Models\UserCommission;
use App\Services\UserCommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCommissionController extends BaseController
{
    private $userCommissionService;

    public function __construct(UserCommissionService $userCommissionService)
    {
        $this->userCommissionService = $userCommissionService;
    }

    /**
     * Obtener comisiones
     * @return mixed
     */
    public function index($id) {
        try {
            $commissions = UserCommission::commissions()
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
     * @return int
     */
    public function update($id, Request $request){
        try {
            $data = $request->all();
            $userCommission = UserCommission::find($id);
            $this->userCommissionService->update($userCommission, $data);

            $commission = UserCommission::commissions()
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
    public function show($id){
        $userCommission = UserCommission::commissions()
            ->where('user_commissions.id', $id)
            ->first();
        if ($userCommission) {
            return response()->json([
                'status' => 200,
                'user_commission' => $userCommission
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Comisión no existe'
        ]);
    }

    /**
     * Eliminar comisión
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            try {
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

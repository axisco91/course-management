<?php

namespace App\Http\Controllers\Api;

use App\Models\AdvisorCommission;
use App\Models\CommissionType;
use App\Services\AdvisorCommissionService;
use App\Services\CommissionTypeService;
use Illuminate\Http\Request;

class CommissionTypeController extends BaseController
{
    private $commissionTypeService;

    public function __construct(CommissionTypeService $commissionTypeService)
    {
        $this->commissionTypeService = $commissionTypeService;
    }

    /**
     * Obtener tipo comisiones
     * @return mixed
     */
    public function index(Request $request) {
        try {
            $commissionTypes = CommissionType::all();

            return $commissionTypes;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    } // end method

    /**
     * Crear tipo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        try {
            $data = $request->all();
            $commissionType = $this->commissionTypeService->create($data);
            return response()->json([
                'status' => 200,
                'commission_type' => $commissionType
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editar tipo comisión
     * @param $id
     * @param Request $request
     * @return int
     */
    public function update($id, Request $request){
        try {
            $data = $request->all();
            $commissionType = CommissionType::find($id);
            $commissionType = $this->commissionTypeService->update($commissionType, $data);
            return response()->json([
                'status' => 200,
                'commission_type' => $commissionType
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener tipo comisión
     * @param $id
     * @return mixed
     */
    public function show($id){
        $commissionType = CommissionType::find($id);
        if ($commissionType) {
            return response()->json([
                'status' => 200,
                'commission_type' => $commissionType
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Comisión no existe'
        ]);
    }

    /**
     * Eliminar tipo comisión
     * @param $id
     * @return int|void
     */
    public function destroy($id){
        if ($id) {
            try {
                CommissionType::destroy($id);
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

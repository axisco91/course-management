<?php

namespace App\Http\Controllers\Api;

use App\Models\CommissionType;
use App\Models\UserCommissionType;
use App\Services\UserCommissionTypeService;
use Illuminate\Http\Request;

class UserCommissionTypeController extends BaseController
{
    private $userCommissionTypeService;

    public function __construct(UserCommissionTypeService $userCommissionTypeService)
    {
        $this->userCommissionTypeService = $userCommissionTypeService;
    }

    /**
     * Obtener tipo comisiones
     * @return mixed
     */
    public function index(Request $request, $id) {
        try {
            $commissionTypes = CommissionType::select('commission_types.*', 'user_commission_types.id as user_commission_type_id', 'user_commission_types.percentage as user_commission_types_percentage')
                ->leftJoin('user_commission_types', function ($join) use ($id) {
                    $join->on('user_commission_types.commission_type_id', '=', 'commission_types.id')
                        ->where('user_commission_types.user_id', '=', $id);
                })
                ->get();
            return response()->json($commissionTypes, 200); // HTTP 200 OK
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            return response()->json([
                'message' => $e->getMessage(),
            ], 500); // HTTP 500 Internal Server Error
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'message' => $e->getMessage(),
            ], 500); // HTTP 500 Internal Server Error
        }
    }

    /**
     * Editar tipo comisión de usuaario
     * @param $id
     * @param Request $request
     * @return int
     */
    public function update(Request $request) {
        try {
            $percentages = json_decode($request->percentages, true);

            if (!is_array($percentages)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid percentage data format',
                ]);
            }

            foreach ($percentages as $key => $percentage) {
                $commissionType = CommissionType::where('name', $key)->first();

                if ($commissionType) {
                    $userCommissionType = UserCommissionType::where('user_id', $request->user_id)
                        ->where('commission_type_id', $commissionType->id)
                        ->first();

                    $data = [
                        'user_id' => $request->user_id,
                        'commission_type_id' => $commissionType->id,
                        'percentage' => $percentage,
                    ];

                    if ($percentage === '') {
                        // Delete an existing UserCommissionType record if it exists and $percentage is empty
                        if ($userCommissionType) {
                            $this->userCommissionTypeService->delete($userCommissionType);
                        }
                    } else {
                        if ($userCommissionType) {
                            // Update the existing UserCommissionType record
                            $this->userCommissionTypeService->update($userCommissionType, $data);
                        } else {
                            // Create a new UserCommissionType record if it doesn't exist
                            $this->userCommissionTypeService->create($data);
                        }
                    }
                }
            }

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

}

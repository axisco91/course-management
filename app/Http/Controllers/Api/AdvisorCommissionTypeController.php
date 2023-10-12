<?php

namespace App\Http\Controllers\Api;

use App\Models\CommissionType;
use App\Models\AdvisorCommissionType;
use App\Services\AdvisorCommissionTypeService;
use Illuminate\Http\Request;

class AdvisorCommissionTypeController extends BaseController
{
    private $advisorCommissionTypeService;

    public function __construct(AdvisorCommissionTypeService $advisorCommissionTypeService)
    {
        $this->advisorCommissionTypeService = $advisorCommissionTypeService;
    }

    /**
     * Obtener tipo comisiones
     * @return mixed
     */
    public function index(Request $request, $id) {
        try {
            $commissionTypes = CommissionType::select('commission_types.*', 'advisor_commission_types.id as advisor_commission_type_id', 'advisor_commission_types.percentage as advisor_commission_types_percentage')
                ->leftJoin('advisor_commission_types', function ($join) use ($id) {
                    $join->on('advisor_commission_types.commission_type_id', '=', 'commission_types.id')
                        ->where('advisor_commission_types.advisor_id', '=', $id);
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
                    $advisorCommissionType = AdvisorCommissionType::where('advisor_id', $request->advisor_id)
                        ->where('commission_type_id', $commissionType->id)
                        ->first();

                    $data = [
                        'advisor_id' => $request->advisor_id,
                        'commission_type_id' => $commissionType->id,
                        'percentage' => $percentage,
                    ];

                    if ($percentage === '') {
                        // Delete an existing advisorCommissionType record if it exists and $percentage is empty
                        if ($advisorCommissionType) {
                            $this->advisorCommissionTypeService->delete($advisorCommissionType);
                        }
                    } else {
                        if ($advisorCommissionType) {
                            // Update the existing advisorCommissionType record
                            $this->advisorCommissionTypeService->update($advisorCommissionType, $data);
                        } else {
                            // Create a new advisorCommissionType record if it doesn't exist
                            $this->advisorCommissionTypeService->create($data);
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

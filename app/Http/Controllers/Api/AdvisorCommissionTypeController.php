<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Resources\AdvisorCommissionTypeResource;
use App\Models\CommissionType;
use App\Models\AdvisorCommissionType;
use Illuminate\Http\Request;

class AdvisorCommissionTypeController extends BaseController
{
    /**
     * Obtener tipo comisiones
     * @return mixed
     */
    public function index(Request $request, $id) {
        try {
            $params = CommissionType::select('commission_types.*', 'advisor_commission_types.id as advisor_commission_type_id', 'advisor_commission_types.percentage as advisor_commission_types_percentage')
                ->leftJoin('advisor_commission_types', function ($join) use ($id) {
                    $join->on('advisor_commission_types.commission_type_id', '=', 'commission_types.id')
                        ->where('advisor_commission_types.advisor_id', '=', $id);
                });
            if ($request->perPage) {
                $commissionTypes = AdvisorCommissionTypeResource::collection($params->paginate(intval(request('perPage'))));
                $paginationData = GeneralHelpers::generatePaginationData($params);
                return $this->sendResponse(
                    [
                        'advisor_commission_types' => $commissionTypes,
                        'links'       => $paginationData['links'],
                        'meta'        => $paginationData['meta'],
                    ],
                    trans('Obtenido')
                );
            }
            $commissionTypes = AdvisorCommissionTypeResource::collection($params->get());

            return $this->sendResponse(
                [
                    'advisor_commission_types' => $commissionTypes,
                ],
                trans('Obtenido')
            );
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
                            $advisorCommissionType->deleteWithService();
                        }
                    } else {
                        if ($advisorCommissionType) {
                            // Update the existing advisorCommissionType record
                            $advisorCommissionType->updateWithService($data);
                        } else {
                            // Create a new advisorCommissionType record if it doesn't exist
                            AdvisorCommissionType::createWithService($data);
                        }
                    }
                }
            }

            return $this->sendResponse(
                [
                ],
                trans('Actualizado correctamente')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Resources\UserCommissionTypeResource;
use App\Models\CommissionType;
use App\Models\UserCommissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCommissionTypeController extends BaseController
{
    /**
     * Obtener tipo comisiones
     * @return mixed
     */
    public function index(Request $request, $id) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = CommissionType::select('commission_types.*', 'user_commission_types.id as user_commission_type_id', 'user_commission_types.percentage as user_commission_types_percentage')
                ->leftJoin('user_commission_types', function ($join) use ($id, $mainCompanyId) {
                    $join->on('user_commission_types.commission_type_id', '=', 'commission_types.id')
                        ->where('user_commission_types.user_id', '=', $id)
                        ->where('user_commission_types.main_company_id', $mainCompanyId);
                });

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $commissionTypes = UserCommissionTypeResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'commission_types' => $commissionTypes,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $commissionTypes = UserCommissionTypeResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'commission_types' => $commissionTypes,
                ],
                trans('Obtenido con éxito')
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
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
                        ->where('main_company_id', $mainCompanyId)
                        ->first();

                    $data = [
                        'user_id' => $request->user_id,
                        'commission_type_id' => $commissionType->id,
                        'percentage' => $percentage,
                    ];

                    if ($percentage === '') {
                        // Delete an existing UserCommissionType record if it exists and $percentage is empty
                        if ($userCommissionType) {
                            $userCommissionType->deleteWithService();
                        }
                    } else {
                        if ($userCommissionType) {
                            // Update the existing UserCommissionType record
                            $userCommissionType->updateWithService($data);
                        } else {
                            // Create a new UserCommissionType record if it doesn't exist
                            UserCommissionType::createWithService($data);
                        }
                    }
                }
            }

            return $this->sendResponse(
                [],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

}

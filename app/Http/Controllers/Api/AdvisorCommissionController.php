<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Resources\AdvisorCommissionResource;
use App\Models\AdvisorCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvisorCommissionController extends BaseController
{
    /**
     * Obtener comisiones
     * @return mixed
     */
    public function index($id, Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = AdvisorCommission::commissions($mainCompanyId)
                ->where('advisor_commissions.advisor_id', $id);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;
                $paginator = $query->paginate($perPage);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'advisor_commissions' => AdvisorCommissionResource::collection($paginator),
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido')
                );
            }

            return $this->sendResponse(
                [
                    'advisor_commissions' => AdvisorCommissionResource::collection($query->get()),
                ],
                trans('Obtenido')
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
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
            $advisorCommission = AdvisorCommission::find($id);
            $advisorCommission->updateWithService($data);

            $commission = AdvisorCommissionResource::collection($advisorCommission);

            return $this->sendResponse(
                [
                    'advisor_commission' => $commission,
                ],
                trans('Obtenido')
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
    public function show($id){
        $advisorCommission = AdvisorCommission::commissions()
            ->where('advisor_commissions.id', $id)
            ->first();

        if ($advisorCommission) {
            $advisorCommission = AdvisorCommissionResource::collection($advisorCommission);

            return $this->sendResponse(
                [
                    'advisor_commission' => $advisorCommission,
                ],
                trans('Obtenido')
            );
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
                AdvisorCommission::destroy($id);
                return $this->sendResponse(
                    [

                    ],
                    trans('Eliminado')
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

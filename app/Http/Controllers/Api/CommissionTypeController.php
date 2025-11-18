<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Models\CommissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionTypeController extends BaseController
{

    /**
     * Obtener tipo comisiones
     * @return mixed
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $commissionTypes = CommissionType::FilterMainCompany($mainCompanyId)->get();

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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $commissionType = CommissionType::createWithService($data);
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $commissionType = CommissionType::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if (!$commissionType){
                return response()->json([
                    'status' => 404,
                    'message' => 'Tipo no encontrado'
                ]);
            }

            $commissionType = $commissionType->updateWithService($data);
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
    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $commissionType = CommissionType::where('id', $id)
            ->FilterMainCompany($mainCompanyId);

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
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $commissiontype = CommissionType::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$commissiontype){
                    return response()->json([
                        'status' => 404,
                        'message' => 'Tipo no encontrado'
                    ]);
                }
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

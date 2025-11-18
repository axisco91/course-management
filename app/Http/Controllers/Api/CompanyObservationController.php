<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\CompanyObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyObservationController extends BaseController
{
    public function companyObservations($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return CompanyObservation::getCompanyObservations($id, $mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $observation = CompanyObservation::createWithService($data);

            return response()->json([
                'status' => 200,
                'observation' => $observation
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $companyObservation = CompanyObservation::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$companyObservation) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Observación no encontrada'
                ]);
            }
            $data = $request->all();

            $companyObservation->updateWithService($data);

            return response()->json([
                'status' => 200,
                'observation' => $companyObservation
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCompanyObservation($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $observation = CompanyObservation::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if ($observation) {
            return response()->json([
                'status' => 200,
                'observation' => $observation
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'observación no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $observation = CompanyObservation::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$observation) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Observación no existe'
                    ]);
                }

                CompanyObservation::destroy($id);
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

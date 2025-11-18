<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Liquidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiquidationController extends BaseController
{
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return Liquidation::getliquidations($mainCompanyId);
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

            $liquidation = Liquidation::createWithService($data);

            return response()->json([
                'status' => 200,
                'module' => Liquidation::getliquidation($liquidation->id, $mainCompanyId),
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

            $liquidation = Liquidation::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$liquidation) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Liquidación no existe'
                ]);
            }
            $data = $request->all();

            $liquidation->updateWithService($data);

            return response()->json([
                'status' => 200,
                'liquidation' => Liquidation::getLiquidation($liquidation->id, $mainCompanyId)
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $liquidation = Liquidation::getLiquidation($id, $mainCompanyId);
        if ($liquidation) {
            return response()->json([
                'status' => 200,
                'liquidation' => $liquidation
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Liquidación no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $liquidation = Liquidation::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$liquidation) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Liquidación no existe'
                    ]);
                }

                Liquidation::destroy($id);
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

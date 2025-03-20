<?php

namespace App\Http\Controllers\Api;
use App\Models\Liquidation;
use Illuminate\Http\Request;

class LiquidationController extends BaseController
{
    public function index(Request $request) {
        try {
            return Liquidation::getliquidations();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $liquidation = Liquidation::createLiquidation($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'module' => Liquidation::getliquidation($liquidation->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $liquidation = Liquidation::updateLiquidation($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'liquidation' => Liquidation::getLiquidation($liquidation->id)
        ]);
    }

    public function show($id){
        $liquidation = Liquidation::getLiquidation($id);
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

    public function destroy($id){
        if ($id) {
            try {
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

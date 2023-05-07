<?php

namespace App\Http\Controllers\API;
use App\Models\Tracing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TracingController extends BaseController
{
    public function getTracings() {
        try {
            return Tracing::getTracings();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $tracing = Tracing::createTracing($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'tracing' => $tracing
        ]);
    }

    public function edit($id, Request $request){
        try {
            $tracing = Tracing::updateTracing($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'tracing' => $tracing
        ]);
    }

    public function getTracing($id){
        $tracing = Tracing::find($id);
        if ($tracing) {
            return response()->json([
                'status' => 200,
                'tracing' => $tracing
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Seguimiento no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Tracing::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return Tracing::count();
    }
}

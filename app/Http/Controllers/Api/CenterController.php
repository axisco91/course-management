<?php

namespace App\Http\Controllers\API;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CenterController extends BaseController
{
    public function getCenters() {
        try {
            return Center::getCenters();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $center = Center::createCenter($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'center' => $center
        ]);
    }

    public function edit($id, Request $request){
        try {
            $center = Center::updateCenter($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'center' => $center
        ]);
    }

    public function getCenter($id){
        $center = Center::find($id);
        if ($center) {
            return response()->json([
                'status' => 200,
                'center' => $center
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Centro no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Center::destroy($id);
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
        return Center::count();
    }
}

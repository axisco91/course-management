<?php

namespace App\Http\Controllers\Api;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends BaseController
{
    public function provinces() {
        try {
            return Province::getProvinces();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $province = Province::createProvince($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'province' => $province
        ]);
    }

    public function edit($id, Request $request){
        try {
            $province = Province::updateProvince($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'province' => $province
        ]);
    }

    public function getProvince($id){
        $province = Province::find($id);
        if ($province) {
            return response()->json([
                'status' => 200,
                'province' => $province
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Provincia no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Province::destroy($id);
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

    public function count(){
        return Province::count();
    }

    public function provincesWithFestivals(Request $request) {
        try {
            if ($request){
                return Province::provincesWithFestivals($request->beginning, $request->end)->get();
            }
            return Province::provincesWithFestivals()->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

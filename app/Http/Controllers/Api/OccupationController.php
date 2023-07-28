<?php

namespace App\Http\Controllers\Api;
use App\Models\Occupation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OccupationController extends BaseController
{
    public function getOccupations() {
        try {
            return Occupation::getOccupations();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $occupation = Occupation::createOccupation($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'occupation' => Occupation::getOccupation($occupation->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $occupation = Occupation::updateOccupation($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'occupation' => Occupation::getOccupation($occupation->id)
        ]);
    }

    public function getOccupation($id){
        $occupation = Occupation::getOccupation($id);
        if ($occupation) {
            return response()->json([
                'status' => 200,
                'occupation' => $occupation
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Ocupación no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Occupation::destroy($id);
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
        return Occupation::count();
    }
}

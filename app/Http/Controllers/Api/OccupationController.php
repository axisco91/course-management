<?php

namespace App\Http\Controllers\API;
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
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $occupation = Occupation::createOccupation($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'occupation' => $occupation
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $occupation = Occupation::updateOccupation($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'occupation' => $occupation
        ]);
    }

    public function getOccupation($id){
        $occupation = Occupation::find($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return Occupation::count();
    }
}

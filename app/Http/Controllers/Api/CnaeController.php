<?php

namespace App\Http\Controllers\API;
use App\Models\Cnae;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CnaeController extends BaseController
{
    public function cnaes() {
        try {
            return Cnae::getCnaes();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $cnae = Cnae::createCnae($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'cnae' => $cnae
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $cnae = Cnae::updateCnae($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'cnae' => $cnae
        ]);
    }

    public function getTrainingActionLevel($id){
        $cnae = Cnae::find($id);
        if ($cnae) {
            return response()->json([
                'status' => 200,
                'cnae' => $cnae
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Cnae no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Cnae::destroy($id);
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
}

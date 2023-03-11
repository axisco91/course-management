<?php

namespace App\Http\Controllers\API;
use App\Models\Modality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ModalityController extends BaseController
{
    public function modalities() {
        try {
            return Modality::getModalities();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $modality = Modality::createModality($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'modality' => $modality
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $modality = Modality::updateModality($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'modality' => $modality
        ]);
    }

    public function getModality($id){
        $modality = Modality::find($id);
        if ($modality) {
            return response()->json([
                'status' => 200,
                'modality' => $modality
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Modalidad no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Modality::destroy($id);
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

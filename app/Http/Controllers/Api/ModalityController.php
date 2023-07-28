<?php

namespace App\Http\Controllers\Api;
use App\Models\Modality;
use Illuminate\Http\Request;

class ModalityController extends BaseController
{
    public function modalities() {
        try {
            return Modality::getModalities();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $modality = Modality::createModality($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'modality' => Modality::getModality($modality->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $modality = Modality::updateModality($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'modality' => Modality::getModality($modality->id)
        ]);
    }

    public function getModality($id){
        $modality = Modality::getModality($id);
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return Modality::count();
    }
}

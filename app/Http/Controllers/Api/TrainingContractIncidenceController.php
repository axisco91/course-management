<?php

namespace App\Http\Controllers\API;
use App\Models\TrainingContractIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractIncidenceController extends BaseController
{
    public function trainingContractIncidences($id) {
        try {
            return TrainingContractIncidence::getTrainingContractIncidences($id);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $incidence = TrainingContractIncidence::createTrainingContractIncidence($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence' => $incidence
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $incidence = TrainingContractIncidence::updateTrainingContractIncidence($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence' => $incidence
        ]);
    }

    public function getTrainingContractIncidence($id){
        $incidence = TrainingContractIncidence::find($id);
        if ($incidence) {
            return response()->json([
                'status' => 200,
                'incidence' => $incidence
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'incidences no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingContractIncidence::destroy($id);
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

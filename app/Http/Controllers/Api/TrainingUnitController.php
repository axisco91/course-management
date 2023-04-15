<?php

namespace App\Http\Controllers\API;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingUnitController extends BaseController
{
    public function trainingUnits() {
        try {
            return TrainingUnit::getTrainingUnits();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $training_unit = TrainingUnit::createTrainingUnit($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_unit' => $training_unit
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $training_unit = TrainingUnit::updateTrainingUnit($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_unit' => $training_unit
        ]);
    }

    public function getTrainingUnit($id){
        $training_unit = TrainingUnit::find($id);
        if ($training_unit) {
            return response()->json([
                'status' => 200,
                'training_unit' => $training_unit
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Unidad formativa no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingUnit::destroy($id);
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
        return TrainingUnit::count();
    }
}

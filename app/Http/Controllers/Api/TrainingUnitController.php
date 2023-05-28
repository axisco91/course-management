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
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $training_unit = TrainingUnit::createTrainingUnit($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_unit' => TrainingUnit::getTrainingUnit($training_unit->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $training_unit = TrainingUnit::updateTrainingUnit($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_unit' => TrainingUnit::getTrainingUnit($training_unit->id)
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return TrainingUnit::count();
    }
}

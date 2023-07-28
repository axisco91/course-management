<?php

namespace App\Http\Controllers\Api;
use App\Models\TrainingActionLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingActionLevelController extends BaseController
{
    public function getTrainingActionLevels() {
        try {
            return TrainingActionLevel::getTrainingActionLevels();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $level = TrainingActionLevel::createTrainingActionLevel($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_action_level' => TrainingActionLevel::getTrainingActionLevel($level->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $level = TrainingActionLevel::updateTrainingActionLevel($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_action_level' => TrainingActionLevel::getTrainingActionLevel($id)
        ]);
    }

    public function getTrainingActionLevel($id){
        $level = TrainingActionLevel::getTrainingActionLevel($id);
        if ($level) {
            return response()->json([
                'status' => 200,
                'training_action_level' => $level
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Nivel no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingActionLevel::destroy($id);
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
        return TrainingActionLevel::count();
    }
}

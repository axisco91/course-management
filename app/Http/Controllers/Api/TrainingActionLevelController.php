<?php

namespace App\Http\Controllers\API;
use App\Models\TrainingActionLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingActionLevelController extends BaseController
{
    public function trainingActionLevels() {
        try {
            return TrainingActionLevel::getTrainingActionLevel();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $level = TrainingActionLevel::createTrainingActionLevel($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_action_level' => $level
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $level = TrainingActionLevel::updateTrainingActionLevel($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_action_level' => $level
        ]);
    }

    public function getTrainingActionLevel($id){
        $level = TrainingActionLevel::find($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

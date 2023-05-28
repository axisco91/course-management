<?php

namespace App\Http\Controllers\API;
use App\Models\TrainingAction;
use App\Models\TrainingActionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingActionGroupController extends BaseController
{
    public function trainingActionGroups() {
        try {
            return TrainingActionGroup::getTrainingActionGroups();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $group = TrainingActionGroup::createTrainingActionGroup($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_action_group' => TrainingActionGroup::getTrainingActionGroup($group->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $group = TrainingActionGroup::updateTrainingActionGroup($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_action_group' => TrainingActionGroup::getTrainingActionGroup($group->id)
        ]);
    }

    public function getTrainingActionGroup($id){
        $group = TrainingActionGroup::getTrainingActionGroup($id);
        if ($group) {
            return response()->json([
                'status' => 200,
                'training_action_group' => $group
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Grupo no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingActionGroup::destroy($id);
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
        return TrainingActionGroup::count();
    }
}

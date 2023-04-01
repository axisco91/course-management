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
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $group = TrainingActionGroup::createTrainingActionGroup($data);
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
        $data = json_decode($request->getContent(), true);
        try {
            $group = TrainingActionGroup::updateTrainingActionGroup($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return TrainingActionGroup::count();
    }
}

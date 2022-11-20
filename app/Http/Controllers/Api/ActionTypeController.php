<?php

namespace App\Http\Controllers\API;
use App\Models\ActionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ActionTypeController extends BaseController
{
    public function getTrainingActionLevels() {
        return ActionType::all();
    }

    public function create(Request $request){
        $data = [
            'name' => $request->name
        ];

        return ActionType::createActionType($data);
    }

    public function edit($id, Request $request){
        $data = [
            'name' =>$request->name
        ];
        $action_type = ActionType::updateActionType($id, $data);
        if ($action_type){
            return 1;
        } else {
            return 0;
        }
    }

    public function getActionType($id){
        return ActionType::find($id);
    }

    public function destroy($id){
        if ($id) {
            ActionType::destroy($id);
            return 1;
        }
    }
}

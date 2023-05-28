<?php

namespace App\Http\Controllers\API;
use App\Models\ActionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ActionTypeController extends BaseController
{
    public function getActionTypes() {
        try {
            return ActionType::getActionTypes();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $action_type = ActionType::createActionType($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'action_type' => ActionType::getActionType($action_type->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $action_type = ActionType::updateActionType($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'action_type' => ActionType::getActionType($action_type->id)
        ]);
    }

    public function getActionType($id){
        $action_type = ActionType::getActionType($id);
        if ($action_type) {
            return response()->json([
                'status' => 200,
                'action_type' => $action_type
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo Acción no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ActionType::destroy($id);
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
        return ActionType::count();
    }
}

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
            return ActionType::getActionType();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $action_type = ActionType::createActionType($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'action_type' => $action_type
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $action_type = ActionType::updateActionType($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'action_type' => $action_type
        ]);
    }

    public function getActionType($id){
        $action_type = ActionType::find($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

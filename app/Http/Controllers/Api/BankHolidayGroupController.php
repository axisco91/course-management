<?php

namespace App\Http\Controllers\API;
use App\Models\ActionType;
use App\Models\BankHolidayGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BankHolidayGroupController extends BaseController
{

    public function getBankHolidayGroups(Request $request) {
        try {
            if ($request) {
                return BankHolidayGroup::getBankHolidayGroup($request['beginning'], $request['end']);
            }
            return BankHolidayGroup::getBankHolidayGroup();
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
            'action_type' => $action_type
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
            'action_type' => $action_type
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
}

<?php

namespace App\Http\Controllers\API;
use App\Models\OnLeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OnLeaveController extends BaseController
{
    public function getOnLeaveTypes() {
        try {
            return OnLeaveType::getOnLeaveTypes();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $on_leave = OnLeaveType::createOnLeaveType($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'on_leave' => OnLeaveType::getOnLeaveType($on_leave->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $on_leave = OnLeaveType::updateOnLeaveType($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'on_leave' => OnLeaveType::getOnLeaveType($on_leave->id)
        ]);
    }

    public function getOnLeaveType($id){
        $on_leave = OnLeaveType::getOnLeaveType($id);
        if ($on_leave) {
            return response()->json([
                'status' => 200,
                'on_leave' => $on_leave
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo de baja no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                OnLeaveType::destroy($id);
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
        return OnLeaveType::count();
    }
}

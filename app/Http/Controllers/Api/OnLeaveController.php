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
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $on_leave = OnLeaveType::createOnLeaveType($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'on_leave' => $on_leave
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $on_leave = OnLeaveType::updateOnLeaveType($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'on_leave' => $on_leave
        ]);
    }

    public function getOnLeaveType($id){
        $on_leave = OnLeaveType::find($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return OnLeaveType::count();
    }
}

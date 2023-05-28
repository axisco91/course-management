<?php

namespace App\Http\Controllers\API;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreditController extends BaseController
{
    public function getCredits($id) {
        try {
            return Credit::getCredits($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $credit = Credit::createCredit($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'credit' => Credit::getCredit($credit->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $credit = Credit::updateCredit($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'credit' => Credit::getCredit($credit->id)
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Credit::destroy($id);
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
        return Credit::count();
    }
}

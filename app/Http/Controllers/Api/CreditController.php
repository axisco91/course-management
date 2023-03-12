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
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $credit = Credit::createCredit($data);
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
        $data = json_decode($request->getContent(), true);
        try {
            $credit = Credit::updateCredit($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

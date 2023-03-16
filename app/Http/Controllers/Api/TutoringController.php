<?php

namespace App\Http\Controllers\API;
use App\Models\Tutoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TutoringController extends BaseController
{
    public function tutorings() {
        try {
            return Tutoring::getTutorings();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $tutoring = Tutoring::createTutoring($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 200,
            'tutoring' => $tutoring
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $tutoring = Tutoring::updateTutoring( $id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'tutring' => $tutoring
        ]);
    }

    public function getTutoring($id){
        $tutoring = Tutoring::find($id);
        if ($tutoring) {
            return response()->json([
                'status' => 200,
                'profitability' => $tutoring
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Rentabilidad no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Tutoring::destroy($id);
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
        return Tutoring::count();
    }
}

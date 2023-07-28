<?php

namespace App\Http\Controllers\Api;
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
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $tutoring = Tutoring::createTutoring($request);
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
        try {
            $tutoring = Tutoring::updateTutoring( $id, $request);
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

    public function tutoring($id){
        $tutoring = Tutoring::find($id);
        if ($tutoring) {
            return response()->json([
                'status' => 200,
                'tutoring' => $tutoring
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tutoria no existe'
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return Tutoring::count();
    }
}

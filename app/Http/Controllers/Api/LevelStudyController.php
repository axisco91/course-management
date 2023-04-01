<?php

namespace App\Http\Controllers\API;
use App\Models\LevelStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LevelStudyController extends BaseController
{
    public function levelStudies() {
        try {
            return LevelStudy::getLevelStudies();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $level_study = LevelStudy::createLevelStudy($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'level_study' => LevelStudy::getLevelStudy($level_study->id)
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $level_study = LevelStudy::updateLevelStudy($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'level_study' => LevelStudy::getLevelStudy($level_study->id)
        ]);
    }

    public function getLevelStudy($id){
        $level_study = LevelStudy::getLevelStudy($id);
        if ($level_study) {
            return response()->json([
                'status' => 200,
                'level_study' => $level_study
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Nivel no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                LevelStudy::destroy($id);
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
        return LevelStudy::count();
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Models\LevelStudy;
use App\Models\TeacherArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherAreaController extends BaseController
{
    public function teacherAreas() {
        try {
            return TeacherArea::getTeacherAreas();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $teacher_area = TeacherArea::createTeacherArea($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'teacher_area' => $teacher_area
        ]);
    }

    public function edit($id, Request $request){
        try {
            $teacher_area = TeacherArea::updateTeacherArea($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'teacher_area' => $teacher_area
        ]);
    }

    public function getTrainingActionLevel($id){
        $teacher_area = TeacherArea::getTeacherArea($id);
        if ($teacher_area) {
            return response()->json([
                'status' => 200,
                'teacher_area' => $teacher_area
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Área no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TeacherArea::destroy($id);
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
        return TeacherArea::count();
    }
}

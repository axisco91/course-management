<?php

namespace App\Http\Controllers\API;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends BaseController
{
    public function getTeachers() {
        try {
            return Teacher::getTeachers();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    // Obtain student
    public function getTeacher($id){
        $teacher = Teacher::find($id);
        if ($teacher) {
            return response()->json([
                'status' => 200,
                'teacher' => $teacher
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Docente no existe'
        ]);
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $teacher = Teacher::createTeacher($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'teacher' => $teacher
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $teacher = Teacher::updateTeacher($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'teacher' => $teacher
        ]);
    }

    public function checkDni(Request $request){
        $data = json_decode($request->getContent(), true);
        $dni = Teacher::findDni($data['dni'], $data['id']);
        if ($dni){
            return response()->json([
                'exists' => true
            ]);
        } else {
            return response()->json([
                'exists' => false
            ]);
        }
    }

    public function getTeachersCourses($id) {
        return Course::getTeachersCourses($id);
    }

    public function destroy($id){
        if ($id) {
            try {
                Teacher::destroy($id);
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

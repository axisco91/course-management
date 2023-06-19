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
                'message' => $e->getMessage()
            ]);
        }
    }

    // Obtain student
    public function getTeacher($id){
        $teacher = Teacher::getTeacher($id);
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
        try {
            $teacher = Teacher::createTeacher($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'teacher' => Teacher::getTeacher($teacher->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $teacher = Teacher::updateTeacher($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'teacher' => Teacher::getTeacher($teacher->id)
        ]);
    }

    public function checkDni(Request $request){
        $dni = Teacher::findDni($request['dni'], $request['id']);
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return Teacher::count();
    }

    public function teachersCSV(Request $request){
        try {
            if ($request) {
                return Teacher::getTeachersCSV($request['name'], $request['surname'], $request['email'], $request['dni'], $request['telephone'], $request['inactive']);
            }
            return Teacher::getTeachersCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

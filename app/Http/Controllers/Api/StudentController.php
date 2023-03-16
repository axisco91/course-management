<?php

namespace App\Http\Controllers\API;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends BaseController
{
    // Obtain all students
    public function getStudents() {
        try {
            return Student::getStudents();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    // Obtain student
    public function getStudent($id){
        $student = Student::find($id);
        if ($student) {
            return response()->json([
                'status' => 200,
                'student' => $student
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $student = Student::createStudent($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'student' => $student
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $student = Student::updateStudent($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'student' => $student
        ]);
    }

    public function checkDni(Request $request){
        $data = json_decode($request->getContent(), true);
        $dni = Student::findDni($data['dni'], $data['id']);
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

    public function getStudentsCourses($id) {
        return $courses = Registration::getStudentCourses($id);
    }

    public function destroy($id){
        if ($id) {
            try {
                Student::destroy($id);
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

    public function countStudents(){
        return Student::count();
    }
}

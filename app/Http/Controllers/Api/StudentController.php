<?php

namespace App\Http\Controllers\API;
use App\Models\BankHolidayGroup;
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
                'message' => $e->getMessage()
            ]);
        }
    }

    // Obtain student
    public function getStudent($id){
        $student = Student::getStudent($id);
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
        try {
            $student = Student::createStudent($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'student' => Student::getStudent($student->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $student = Student::updateStudent($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'student' => Student::getStudent($student->id)
        ]);
    }

    public function checkDni(Request $request){
        $dni = Student::findDni($request['dni'], $request['id']);
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function countStudents(){
        return Student::count();
    }

    public function studentsCSV(Request $request){
        try {
            if ($request) {
                return Student::getStudentCSV($request['name'], $request['surname'], $request['dni'], $request['telephone'], $request['email'], $request['company'], $request['inactive']);
            }
            return Student::getStudentCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getActiveStudents(Request $request) {
        try {
            return Student::getActiveStudents();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

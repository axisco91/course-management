<?php

namespace App\Http\Controllers\API;
use App\Mail\PotentialPrivateStudent as PotentialPrivateEmail;
use App\Mail\PotentialStudent as PotentialEmail;
use App\Models\PotentialStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class PotentialStudentController extends BaseController
{
    public function getPotentialStudents() {
        try {
            return PotentialStudent::getPotentialStudents();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getPotentialStudent($id) {
        $potential_student = PotentialStudent::getPotentialStudent($id);
        if ($potential_student) {
            return response()->json([
                'status' => 200,
                'student' => $potential_student
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    public function create(Request $request){
        try {
            $student = PotentialStudent::createPotentialStudent($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'potential_student' => $student
        ]);
    }

    public function edit($id, Request $request){
        try {
            $student = PotentialStudent::updateStudent($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'potential_student' => $student
        ]);
    }

    public function getTrainingActionLevel($id){
        $student = PotentialStudent::find($id);
        if ($student) {
            return response()->json([
                'status' => 200,
                'potential_student' => $student
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                PotentialStudent::destroy($id);
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

    public function sendEmail(Request $request){
        if ($request['email']){
            try {
                Mail::to($request['email'])->send(new PotentialPrivateEmail());
                return response()->json([
                    'status' => 200
                ]);
            } catch(Exception $e) {

            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Error al enviar correo'
        ]);
    }

    public function sendBonusEmail(Request $request){
        if ($request['email']){
            try {
                Mail::to($request['email'])->send(new PotentialEmail());
                return response()->json([
                    'status' => 200
                ]);
            } catch(Exception $e) {

            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Error al enviar correo'
        ]);
    }

    public function checkDni(Request $request){
        $dni = PotentialStudent::findDni($request['dni'], $request['id']);
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

    public function count(){
        return PotentialStudent::count();
    }
}

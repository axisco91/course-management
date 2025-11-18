<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Mail\PotentialPrivateStudent as PotentialPrivateStudent;
use App\Mail\PotentialStudent as PotentialEmail;
use App\Models\MainCompany;
use App\Models\PotentialStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class PotentialStudentController extends BaseController
{
    public function getPotentialStudents(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return PotentialStudent::getPotentialStudents($mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPotentialStudent($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $potentialStudent = PotentialStudent::getPotentialStudent($id, $mainCompanyId);
        if ($potentialStudent) {
            return response()->json([
                'status' => 200,
                'student' => $potentialStudent
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $student = PotentialStudent::createWithService($data);
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $student = PotentialStudent::updateStudent($id, $request);
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

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $potentialStudent = PotentialStudent::where('id', $id)
                    ->FilterMainCompanyId($mainCompanyId)
                    ->first();

                if (!$potentialStudent) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Alumno potencial no encontrado'
                    ]);
                }

                PotentialStudent::destroy($id);
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

    public function sendEmail(Request $request){
        if ($request['email']){
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $mainCompany = MainCompany::find($mainCompanyId);

                Mail::getSwiftMailer()
                    ->getTransport()
                    ->setUsername('zona@avzformacion.com')
                    ->setPassword('Avz.2021');
                    Mail::to($request['email'])->send(new PotentialPrivateStudent());
                    return response()->json([
                    'status' => 200
                ]);
            } catch(Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
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
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $mainCompany = MainCompany::find($mainCompanyId);

                Mail::getSwiftMailer()
                    ->getTransport()
                    ->setUsername('zona@avzformacion.com')
                    ->setPassword('Avz.2021');
                Mail::to($request['email'])->send(new PotentialEmail());
                return response()->json([
                    'status' => 200
                ]);
            } catch(Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Error al enviar correo'
        ]);
    }

    public function checkDni(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $dni = PotentialStudent::findDni($request['dni'], $mainCompanyId, $request['id']);
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

    public function count(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return PotentialStudent::FilterMainCompany($mainCompanyId)->count();
    }

    public function convertStudent($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $potentialStudent = PotentialStudent::where('id', $id)
                ->FilterMainCompanyId($mainCompanyId)
                ->first();

            if (!$potentialStudent) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Alumno potencial no encontrado'
                ]);
            }

            Student::createWithService($request);
            $potentialStudent->convertPotentialStudent();
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'student' => $potentialStudent
        ]);
    }
}

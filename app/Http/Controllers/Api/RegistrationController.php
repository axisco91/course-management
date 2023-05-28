<?php

namespace App\Http\Controllers\API;
use App\Models\Advisor;
use App\Models\Billing;
use App\Models\Chore;
use App\Models\Company;
use App\Models\Profitability;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Tracing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends BaseController
{
    public function getRegistrations($id) {
        return  Registration::getRegistrated($id);
    }

    public function getNotRegistered($id) {
        return  Registration::getUnregistrated($id);
    }

    public function create(Request $request){
        try {
            $student = Student::getStudent($request['student_id']);
            $tracing_data = [
                'course_id' => $request['course_id'],
                'company_id' => $student['company_id'],
                'student_id' => $student['id'],
            ];
            $tracing = Tracing::createTracing($tracing_data);
            if ($tracing) {
                $chore_data = [
                    'course_id' => $request['course_id'],
                    'company_id' => $student['company_id'],
                    'student_id' => $student['id']
                ];
                $chore = Chore::createChore($chore_data);
                if ($chore) {
                    $advisor_id = null;
                    $collaborator_id = null;
                    $company = Company::find($student['company_id']);
                    $advisor = Advisor::find($company->advisor_id);
                    $advisor_percentage = null;
                    $collaborator_percentage = null;
                    if ($advisor) {
                        if ($advisor['collaborator_id']){
                            $collaborator_id = $advisor['collaborator_id'];
                        }
                        if ($advisor['commission']){
                            $advisor_percentage = intval($advisor['commission']);
                        }
                    }
                    if ($company){
                        if ($company['advisor_id']){
                            $advisor_id = $company['advisor_id'];
                        }
                        if ($company['collaborator_id']){
                            $collaborator_id = $company['collaborator_id'];
                        }
                    }
                    if ($collaborator_id){
                        $user = User::find($collaborator_id);
                        if ($user){
                            $collaborator_percentage = $user['commission'];
                        }
                    }
                    $billing_data = [
                        'course_id' => $request['course_id'],
                        'company_id' => $student['company_id'],
                        'is_bonus' => $request['is_bonus'],
                        'price' => $request['price'],
                        'student_id' => $student['id'],
                        'advisor_id' => $advisor_id,
                        'collaborator_id' => $collaborator_id,
                    ];
                    $billing = Billing::updateBillingRegistrations($billing_data);
                    $profitability_data =[
                        'course_id' =>$request['course_id'],
                        'company_id' => $student['company_id'],
                        'student_id' => $student['id'],
                        'price' => $request['price'],
                        'total' => $request['price'],
                        'advisor_percentage' => $advisor_percentage,
                        'collaborator_percentage' => $collaborator_percentage,
                        'is_bonus' => $request['is_bonus']
                    ];
                    $profitability = Profitability::createProfitability($profitability_data);
                    $registration_data = [
                        'course_id' => $request['course_id'],
                        'company_id' => $student['company_id'],
                        'student_id' => $student['id'],
                        'billing_id' => $billing['id'],
                        'tracing_id' => $tracing['id'],
                        'chore_id' => $chore['id'],
                        'price' => $request['price'],
                        'profitability_id' => $profitability['id'],
                        'is_bonus' => $request['is_bonus']
                    ];
                    $registration = Registration::createRegistration($registration_data);
                    $student['registration_id'] = $registration->id;
                }
            }

        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'registration' => $registration,
            'student' => $student
        ]);
    }

    public function getRegistration($id){
        $registration = Registration::find($id);
        if ($registration) {
            return response()->json([
                'status' => 200,
                'registration' => $registration
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Matriculación no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                $registration = Registration::find($id);
                $student = Student::getStudent($registration['student_id']);
                Registration::unregistration($registration['student_id'], $registration['course_id']);
                return response()->json([
                    'status' => 200,
                    'student' => $student
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

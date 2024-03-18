<?php

namespace App\Http\Controllers\Api;
use App\Models\Advisor;
use App\Models\AdvisorCommission;
use App\Models\Bill;
use App\Models\CommissionType;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseOrigin;
use App\Models\CourseType;
use App\Models\Profitability;
use App\Models\Registration;
use App\Models\Student;
use App\Models\TrainingAction;
use App\Models\User;
use App\Services\AdvisorCommissionService;
use App\Services\BillService;
use App\Services\ProfitabilityService;
use App\Services\RegistrationService;
use Illuminate\Http\Request;

class RegistrationController extends BaseController
{
    private $registrationService;
    private $billService;
    private $profitabilityService;
    private $advisorCommissionService;

    public function __construct(RegistrationService $registrationService, BillService $billService, ProfitabilityService $profitabilityService, AdvisorCommissionService  $advisorCommissionService)
    {
        $this->registrationService = $registrationService;
        $this->billService = $billService;
        $this->profitabilityService = $profitabilityService;
        $this->advisorCommissionService = $advisorCommissionService;
    }

    /**
     * Obtenemos los alumnos matriculados
     * @param $id
     * @return mixed
     */
    public function getRegistrations($id) {
        return Student::getRegistrated($id)
            ->get();
    }

    /**
     * Obtenemos los alumnos no matriculados
     * @param $id
     * @return mixed
     */
    public function getNotRegistered($id) {
        return Student::getUnregistrated($id)
            ->get();
    }

    /**
     * Creamos la matriculación
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        try {
            $student = Student::find($request['student_id']);
            // Obtenemos los datos de asesorías y colaboradores
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
            // Vemos si ya existe una factura sino creamos otro
            $bill = Bill::where('course_id', $request->course_id)
                ->where('company_id', $company->id)
                ->where('is_bonus', $request->is_bonus)->first();
            $billData = [
                'course_id' => $request['course_id'],
                'company_id' => $student['company_id'],
                'is_bonus' => $request['is_bonus'],
                'price' => $request['price'],
                'student_id' => $student['id'],
                'advisor_id' => $advisor_id,
                'collaborator_id' => $collaborator_id,
                'company_name' => $company['name']
            ];
            if ($bill && $company['name'] != 'SIN EMPRESA'){
                $bill = $this->billService->updateBillingRegistrations($bill, $billData);
            } else {
                $bill = $this->billService->createBillingRegistrations($billData);
            }

            // Para crear las comisiones primero tenemos que asegurar que tiene una asesoría
            if ($advisor_id) {
                $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                    ->where('commissionable_type', 'App\Models\Bill')
                    ->where('advisor_id', $advisor_id)
                    ->first();

                // Buscamos el curso de que pertenece esta matriculación
                $course = Course::where('id' , $request['course_id'])
                    ->first();
                if ($course) {
                    $commissionType = null;
                    // Obtenemos la acción formativa para ver que origen tiene
                    $trainingAction = TrainingAction::find($course->training_action_id);
                    if ($trainingAction->course_origin_id) {
                        // Vemos si existe un tipo de comisión con el nombre de origen
                        $courseOrigin = CourseOrigin::find($trainingAction->course_origin_id);
                        $commissionType = CommissionType::where('name', $courseOrigin->name)
                            ->first();
                    }
                    // Si no existe ya miramos el tipo de curso para crear la comisión
                    if (!$commissionType) {
                        $courseType = CourseType::find($course->course_type_id);
                        $commissionType = CommissionType::where('name', $courseType->name)
                            ->first();
                    }
                    if ($commissionType) {
                        $commissionData = [
                            'advisor_id' => $advisor_id,
                            'course_id' => $request['course_id'],
                            'commissionable_id' => $bill->id,
                            'commissionable_type' => 'App\Models\Bill',
                            'commission_type_id' => $commissionType->id,
                            'percentage' => $commissionType->percentage,
                            'amount' => ($commissionType->percentage / 100) * $bill->billing,
                            'bill_amount' => $bill->billing
                        ];
                        if ($advisorCommission) {
                            $this->advisorCommissionService->update($advisorCommission, $commissionData);
                        } else {
                            $this->advisorCommissionService->create($commissionData);
                        }
                    }
                }
            }

            // Vemos si existe la rentabilidad sino creamos otra
            $profitabilityData =[
                'course_id' =>$request['course_id'],
                'company_id' => $student['company_id'],
                'student_id' => $student['id'],
                'price' => $request['price'],
                'total' => $request['price'],
                'advisor_percentage' => $advisor_percentage,
                'collaborator_percentage' => $collaborator_percentage,
                'is_bonus' => $request['is_bonus']
            ];
            if ($request->is_bonus) {
                $profitabilityData['student_id'] = null;
                $profitability = Profitability::select('profitabilities.*')->leftjoin('registrations', 'registrations.profitability_id', '=', 'profitabilities.id')
                    ->where('profitabilities.course_id', $request->course_id)
                    ->where('profitabilities.company_id', $request->company_id)
                    ->where('registrations.is_bonus', $request->is_bonus)->first();
                if ($profitability) {
                    $profitability = $this->profitabilityService->updateRegistration($profitability, $profitabilityData);
                } else {
                    $profitability = $this->profitabilityService->create($profitabilityData);
                }
            } else {
                $profitability = $this->profitabilityService->create($profitabilityData);
            }

            // Creamos la matriculación junto con los datos de tareas y seguimiento
            $data = [
                'course_id' => $request['course_id'],
                'company_id' => $student['company_id'],
                'student_id' => $student['id'],
                'advisor_id' => $advisor_id,
                'collaborator_id' => $collaborator_id,
                'price' => $request['price'],
                'profitability_id' => $profitability['id'],
                'is_bonus' => $request['is_bonus'],
                'billing_id' => $bill->id
            ];
            $registration = $this->registrationService->create($data);
            $student['registration_id'] = $registration->id;

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

    /**
     * Obtenemos la matriculación
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Eliminamos la matriculación
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                $registration = Registration::find($id);
                $student = Student::find($registration['student_id']);
                $this->registrationService->destroy($registration);
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
    /**
     * Obtiene todos los registros (registrations).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRegistrations() {
        try {
            $registrations = Registration::all();
            return response()->json([
                'status' => 200,
                'registrations' => $registrations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
}

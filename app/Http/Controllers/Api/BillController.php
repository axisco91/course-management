<?php

namespace App\Http\Controllers\Api;
use App\Models\AdvisorCommission;
use App\Models\AdvisorCommissionType;
use App\Models\Bill;
use App\Models\Chore;
use App\Models\CommissionType;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseOrigin;
use App\Models\CourseType;
use App\Models\Registration;
use App\Models\Student;
use App\Models\TrainingAction;
use App\Models\UserCommission;
use App\Models\UserCommissionType;
use App\Services\AdvisorCommissionService;
use App\Services\BillService;
use App\Services\UserCommissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillController extends BaseController
{
    private $billService;
    private $advisorCommissionService;
    private $userCommissionService;

    public function __construct(BillService $billService, AdvisorCommissionService $advisorCommissionService, UserCommissionService $userCommissionService)
    {
        $this->billService = $billService;
        $this->advisorCommissionService = $advisorCommissionService;
        $this->userCommissionService = $userCommissionService;
    }

    /**
     * Obtener facturas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            $cfa = CourseType::where('name', 'CFA')->first();
            $bills = Bill::bill();
            if ($cfa) {
                $bills = $bills->where('course_type_id', '!=', $cfa->id);
            }

            if ($request->course) {
                $bills = $bills->where('training_actions.name', 'like', '%'.$request->course.'%');
            }
            if ($request->company) {
                $bills = $bills->where('companies.name', 'like', '%'.$request->company.'&');
            }
            if ($request->type) {
                if ($request->type === 'No bonificada') {
                    $bills = $bills->where('billings.is_bonus', 1);
                } else if ($request->type === 'Bonificada') {
                    $bills = $bills->where('billings.is_bonus', 0);
                }
            }
            if ($request->invoiced) {
                if ($request->invoiced === 'Si') {
                    $bills = $bills->where('billings.invoiced', 1);
                } else if ($request->invoiced === 'No') {
                    $bills = $bills->where('billings.invoiced', 0);
                }
            }
            if ($request->charged) {
                if ($request->charged === 'Si') {
                    $bills = $bills->where('billings.charge', 1);
                } else if ($request->charged === 'No') {
                    $bills = $bills->where('billings.charge', 0);
                }
            }

            $bills = $bills->orderBy('courses.beginning', 'desc')->get();
            return $bills;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener factura
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $bill = Bill::bill()
            ->where('billings.id', $id)
            ->first();
        if ($bill) {
            $course = Course::where('id', $bill->course_id)->first();
            $company = Company::where('id', $bill->company_id)->first();
            $bill['name'] = $course->name .' - '. $company->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return response()->json([
                'status' => 200,
                'billing' => $bill
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Factura no existe'
        ]);
    }

    /**
     * Crear factura
     * @param Request $request
     * @return void
     */
    public function store(Request $request){

    }

    /**
     * Editar factura
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        try {
            $bill = Bill::find($id);
            $request['is_bonus'] = $bill->is_bonus;
            $data = $request->all();
            $element = $this->billService->update($bill, $data);

            // Buscamos el curso de que pertenece esta matriculación
            $course = Course::where('id' , $bill->course_id)
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
                    // Para crear las comisiones primero tenemos que asegurar que tiene una asesoría
                    if ($bill->advisor_id) {
                        $percentage = $commissionType->percentage;
                        $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                            ->where('commissionable_type', 'App\Models\Bill')
                            ->where('advisor_id', $bill->advisor_id)
                            ->first();
                        $advisorCommissionType = AdvisorCommissionType::where('advisor_id', $bill->advisor_id)
                            ->where('commission_type_id', $commissionType->id)->first();
                        if ($advisorCommissionType) {
                            $percentage = $advisorCommissionType->percentage;
                        }
                        $commissionData = [
                            'advisor_id' => $bill->advisor_id,
                            'course_id' => $bill->course_id,
                            'commissionable_id' => $bill->id,
                            'commissionable_type' => 'App\Models\Bill',
                            'commission_type_id' => $commissionType->id,
                            'percentage' => $percentage,
                            'amount' => ($percentage / 100) * $bill->billing,
                            'bill_amount' => $bill->billing
                        ];
                        if ($advisorCommission) {
                            $this->advisorCommissionService->update($advisorCommission, $commissionData);
                        } else {
                            $this->advisorCommissionService->create($commissionData);
                        }
                    }
                    if ($bill->collaborator_id) {
                        $percentage = $commissionType->percentage;
                        $userCommission = UserCommission::where('commissionable_id', $bill->id)
                            ->where('commissionable_type', 'App\Models\Bill')
                            ->where('user_id', $bill->collaborator_id)
                            ->first();
                        $userCommissionType = UserCommissionType::where('user_id', $bill->collaborator_id)
                            ->where('commission_type_id', $commissionType->id)->first();
                        if ($userCommissionType) {
                            $percentage = $userCommissionType->percentage;
                        }
                        $commissionData = [
                            'user_id' => $bill->collaborator_id,
                            'course_id' => $bill->course_id,
                            'commissionable_id' => $bill->id,
                            'commissionable_type' => 'App\Models\Bill',
                            'commission_type_id' => $commissionType->id,
                            'percentage' => $percentage,
                            'amount' => ($percentage / 100) * $bill->billing,
                            'bill_amount' => $bill->billing
                        ];
                        if ($userCommission) {
                            $this->userCommissionService->update($userCommission, $commissionData);
                        } else {
                            $this->userCommissionService->create($commissionData);
                        }
                    }
                }
            }

            $registrations = Registration::billingRegistration($id)
                ->get();
            foreach ($registrations as $registration){
                $chore = Chore::find($registration->chore_id);
                $chore->update([
                    'bonus_sent_status' => $bill['invoiced'],
                    'bonus_sent_date' => $request['billing_date'] ? \Illuminate\Support\Carbon::createFromFormat('d-m-Y', $request['billing_date'])->format('Y-m-d') : null,
                    'invoiced_status' => $bill['invoiced'],
                    'invoiced_date' => $request['billing_date'] ? Carbon::createFromFormat('d-m-Y', $request['billing_date'])->format('Y-m-d') : null
                ]);
            }

            $bill = Bill::bill()
                ->where('billings.id', $element->id)
                ->first();
            if ($bill) {
                $course = Course::where('id', $bill->course_id)->first();
                $company = Company::where('id', $bill->company_id)->first();
                $bill['name'] = $course->group . '/' . $course->name . ' - ' . $company->name . ' ' . Carbon::parse($course->beginning)->format('d/m/Y') . ' - ' . Carbon::parse($course->end)->format('d/m/Y');
            }
            return response()->json([
                'status' => 200,
                'billing' => $bill
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminamos factura
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                $bill = Bill::find($id);
                if ($bill) {
                    $advisorCommissions = AdvisorCommission::where('commissionable_id', $bill->id)
                        ->where('commissionable_type', 'App\Models\Bill')
                        ->get();
                    foreach ($advisorCommissions as $advisorCommission) {
                        AdvisorCommission::destroy($advisorCommission->id);
                    }
                }
                Bill::destroy($id);
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

    /**
     * Obtenemos los alumnos de la factura
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBillStudents($id)
    {
        return response()->json(Student::billedStudent($id)->get());
    }
}

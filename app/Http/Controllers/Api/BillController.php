<?php

namespace App\Http\Controllers\Api;
use App\Models\AdvisorCommission;
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
use App\Services\AdvisorCommissionService;
use App\Services\BillService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillController extends BaseController
{
    private $billService;
    private $advisorCommissionService;

    public function __construct(BillService $billService, AdvisorCommissionService $advisorCommissionService)
    {
        $this->billService = $billService;
        $this->advisorCommissionService = $advisorCommissionService;
    }

    /**
     * Obtener facturas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        try {
            $cfa = CourseType::where('name', 'CFA')->first();
            $bills = Bill::bill();
            if ($cfa) {
                $bills = $bills->where('course_type_id', '!=', $cfa->id);
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
    public function create(Request $request){

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
            // Para crear las comisiones primero tenemos que asegurar que tiene una asesoría
            if ($bill->advisor_id) {
                $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                    ->where('commissionable_type', 'App\Models\Bill')
                    ->where('advisor_id', $bill->advisor_id)
                    ->first();

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
                        $commissionData = [
                            'advisor_id' => $bill->advisor_id,
                            'course_id' => $bill->course_id,
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

    /**
     * Obtenemos el csv de las facturas
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function billsCSV(Request $request){
        try {
            $bills = Bill::bill();

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
            $cfa = CourseType::where('name', 'CFA')->first();
            if ($cfa) {
                $bills = $bills->where('course_type_id', '!=', $cfa->id);
            }

            $bills = $bills->orderBy('courses.beginning', 'desc')->get();
            return $bills;
            $data = [];
            if (count($bills) > 0) {
                foreach ($bills as $bill) {
                    $element = [
                        'Nº Factura' => $bill['billing_number'],
                        'Curso' => $bill['course'],
                        'Año' => $bill['year'],
                        'Tipo' => $bill['type'],
                        'Empresa' => $bill['company'],
                        'Asesoría' => $bill['advisor'],
                        'Collaborador' => $bill['collaborator'],
                        'Numero Alumnos' => $bill['number_students'],
                        'Factura' => $bill['billing'],
                        'Fecha Factura' => $bill['billing_date'],
                        'Fecha Cobro' => $bill['collection_date'],
                        'Cobrado' => $bill['charge']
                    ];
                    $data[] = $element;
                }
            } else {
                $element = [
                    'Nº Factura' => '',
                    'Curso' => '',
                    'Año' => '',
                    'Tipo' => '',
                    'Empresa' => '',
                    'Asesoría' => '',
                    'Collaborador' => '',
                    'Numero Alumnos' => '',
                    'Factura' => '',
                    'Fecha Factura' => '',
                    'Fecha Cobro' => '',
                    'Cobrado' => ''
                ];
                $data[] = $element;
            }
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

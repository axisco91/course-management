<?php

namespace App\Http\Controllers\Api;
use App\Models\Bill;
use App\Models\Chore;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Student;
use App\Services\BillService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillController extends BaseController
{
    private $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    /**
     * Obtener facturas
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBills() {
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
    public function getBill($id){
        $bill = Bill::bill()
            ->where('billings.id', $id)
            ->first();
        if ($bill) {
            $course = Course::where('id', $bill->course_id)->first();
            $company = Company::where('id', $bill->company_id)->first();
            $bill['name'] = $course->group.'/'. $course->name .' - '. $company->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
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
    public function edit($id, Request $request){
        try {
            $bill = Bill::find($id);
            $request['is_bonus'] = $bill->is_bonus;
            $data = $request->all();
            $element = $this->billService->update($bill, $data);

            Chore::billingDateChore($id, $request['billing_date'], $bill['invoiced']);
            $bill = Bill::bill()
                ->where('billings.id', $id)
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

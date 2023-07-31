<?php

namespace App\Http\Controllers\Api;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Profitability;
use App\Models\Student;
use App\Services\ProfitabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitabilityController extends BaseController
{
    private $profitabilityService;

    public function __construct(ProfitabilityService $profitabilityService)
    {
        $this->profitabilityService = $profitabilityService;
    }

    /**
     * Obtener rentabilidad
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfitabilities() {
        try {
            $profitabilities = Profitability::profitability();
            $cfa = CourseType::where('name', 'CFA')->first();
            if ($cfa) {
                $profitabilities = $profitabilities->where('course_type_id', '!=', $cfa->id);
            }

            $profitabilities = $profitabilities
                ->orderBy('courses.beginning', 'desc')
                ->get();
            return $profitabilities;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfitability($id){
        $profitability = Profitability::profitability()
            ->where('profitabilities.id', $id)
            ->first();
        if ($profitability) {
            $course = Course::where('id', $profitability->course_id)->first();
            if ($course) {
                $beginning = Carbon::parse($course->beginning)->format('d/m/Y');
                $end = Carbon::parse($course->end)->format('d/m/Y');
                $profitability['name'] = $course->group.'/'. $course->name .' '.$beginning.' - '.$end;
                return response()->json([
                    'status' => 200,
                    'profitability' => $profitability
                ]);
            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Rentabilidad no existe'
        ]);
    }

    /**
     * Crear rentabilidad
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        try {
            $data = $request->all();
            $profitability = $this->profitabilityService->create($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'profitability' => Profitability::profitability()
                ->where('profitabilities.id', $profitability->id)
                ->first()
        ]);
    }

    /**
     * Editar rentabbilidad
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request){
        try {
            $data = $request->all();
            $profitability = Profitability::find($id);
            $profitability = $this->profitabilityService->update($profitability, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'profitability' => Profitability::profitability()
                ->where('profitabilities.id', $profitability->id)
                ->first()
        ]);
    }

    /**
     * Eliminar rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Profitability::destroy($id);
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
     * Obtenemos los alumnos que están en esa rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudents($id){
        $profitabilities = Profitability::find($id);
        $registrations = $profitabilities->registrations()->get()->pluck('student_id')->toArray();
        $students = Student::select('students.*', 'companies.name as company_name')
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->whereIn('students.id', $registrations)->get();
        return response()->json($students);
    }

    /**
     * Obtener CSV de las rentabilidades
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function profitsCSV(Request $request){
        try {
            $profits = Profitability::profitability();
            if ($request->course) {
                $profits = $profits->where('courses.name', 'like', '%'.$request->course.'%');
            }
            if ($request->company) {
                $profits = $profits->where('companies.name', 'like', '%'.$request->company.'&');
            }
            if ($request->status) {
                $profits = $profits->where('course_statuses.name', 'like', '%'.$request->status.'%');
            }
            $profits = $profits->orderBy('courses.beginning', 'desc')->get();
            $cfa = CourseType::where('name', 'CFA')->first();
            if ($cfa) {
                $profits = $profits->where('course_type_id', '!=', $cfa->id);
            }
            $data = [];
            if (count($profits) > 0) {
                foreach ($profits as $profit) {
                    $element = [
                        'Curso' => $profit['course'],
                        'Año' => $profit['year'],
                        'Empresa' => $profit['company_name'],
                        'Alumnos' => $profit['student'],
                        'Precio' => $profit['price'],
                        'Licencia' => $profit['license'],
                        'Docente' => $profit['teacher'],
                        'Gestión' => $profit['management'],
                        'Titulo Nebrija' => $profit['nebrija_title'],
                        'Descuento' => $profit['discount'],
                        'Comisión Colaborador' => $profit['collaborator_commission'],
                        'Comisión Asesoría' => $profit['advisor_commission'],
                        'Total' => $profit['total'],
                        'Beneficio' => $profit['benefits'],
                        'Rentabilidad' => $profit['rentabilidad']
                    ];
                    $data[] = $element;
                }
            } else {
                $element = [
                    'Curso' => '',
                    'Año' => '',
                    'Empresa' => '',
                    'Alumnos' => '',
                    'Precio' => '',
                    'Licencia' => '',
                    'Docente' => '',
                    'Gestión' => '',
                    'Titulo Nebrija' => '',
                    'Descuento' => '',
                    'Comisión Colaborador' => '',
                    'Comisión Asesoría' => '',
                    'Total' => '',
                    'Beneficio' => '',
                    'Rentabilidad' => ''
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

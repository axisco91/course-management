<?php

namespace App\Http\Controllers\Api;
use App\Models\Course;
use App\Models\Student;
use App\Models\Tracing;
use App\Services\TracingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TracingController extends BaseController
{

    private $tracingService;

    public function __construct(TracingService $tracingService)
    {
        $this->tracingService = $tracingService;
    }

    /**
     * Obtener los seguimientos
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTracings() {
        try {
            $start = \Illuminate\Support\Carbon::now();
            $number_days = 5;
            if ($start->dayOfWeek >= 2)
                $number_days = 7;
            $start = $start->addDays($number_days);
            $tracings = Tracing::tracing();

             if (Auth::user()->hasRole('Docente')) {
                 $tracings = $tracings->leftjoin('registrations', 'registrations.tracing_id', '=', 'tracings.id')
                     ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                     ->where('courses.teacher_id', Auth::user()->teacher_id);
             }

            $tracings = $tracings->orderBy('tracings.id', 'desc')
                ->get();
            foreach ($tracings as $tracing){
                if ($tracing->final_test === 0) {
                    $tracing['final_test_name'] = 'Pendiente';
                } else if ($tracing->final_test === 1) {
                    $tracing['final_test_name'] = 'Realizado';
                } else if ($tracing->final_test === 2) {
                    $tracing['final_test_name'] = 'No realizado';
                }
                if ($tracing->questionnaire === 0) {
                    $tracing['questionnaire_name'] = 'Pendiente';
                } else if ($tracing->questionnaire === 1) {
                    $tracing['questionnaire_name'] = 'Realizado';
                } else if ($tracing->questionnaire === 2) {
                    $tracing['questionnaire_name'] = 'No realizado';
                }
            }
            return $tracings;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getTracing($id){
        $tracing = Tracing::tracing()
            ->where('tracings.id', $id)
            ->first();
        if ($tracing) {
            if ($tracing->final_test === 0) {
                $tracing['final_test_name'] = 'Pendiente';
            } else if ($tracing->final_test === 1) {
                $tracing['final_test_name'] = 'Realizado';
            } else if ($tracing->final_test === 2) {
                $tracing['final_test_name'] = 'No realizado';
            }
            if ($tracing->questionnaire === 0) {
                $tracing['questionnaire_name'] = 'Pendiente';
            } else if ($tracing->questionnaire === 1) {
                $tracing['questionnaire_name'] = 'Realizado';
            } else if ($tracing->questionnaire === 2) {
                $tracing['questionnaire_name'] = 'No realizado';
            }
            $course = Course::where('id', $tracing->course_id)->first();
            $student = Student::where('id', $tracing->student_id)->first();
            $tracing['name'] = $course->group.'/'. $course->name .' - '. $student->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return response()->json([
                'status' => 200,
                'tracing' => $tracing
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Seguimiento no existe'
        ]);
    }

    /**
     * Crear seguimiento
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        /*
        try {
            $data = $request->all();
            $element = $this->profitabilityService->create($data);
            $tracing = Tracing::tracing()
                ->where('tracings.id', $element->id)
                ->first();
            if ($tracing) {
                if ($tracing->final_test === 0) {
                    $tracing['final_test_name'] = 'Pendiente';
                } else if ($tracing->final_test === 1) {
                    $tracing['final_test_name'] = 'Realizado';
                } else if ($tracing->final_test === 2) {
                    $tracing['final_test_name'] = 'No realizado';
                }
                if ($tracing->questionnaire === 0) {
                    $tracing['questionnaire_name'] = 'Pendiente';
                } else if ($tracing->questionnaire === 1) {
                    $tracing['questionnaire_name'] = 'Realizado';
                } else if ($tracing->questionnaire === 2) {
                    $tracing['questionnaire_name'] = 'No realizado';
                }
                $course = Course::where('id', $tracing->course_id)->first();
                $student = Student::where('id', $tracing->student_id)->first();
                $tracing['name'] = $course->group.'/'. $course->name .' - '. $student->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            }
            return response()->json([
                'status' => 200,
                'tracing' => $tracing
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        */
    }

    /**
     * Editar rentabilidad
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request){
        try {
            $tracing = Tracing::find($id);
            $data = $request->all();
            $element = $this->tracingService->update($tracing, $data);
            $tracing = Tracing::tracing()
                ->where('tracings.id', $element->id)
                ->first();
            if ($tracing) {
                if ($tracing->final_test === 0) {
                    $tracing['final_test_name'] = 'Pendiente';
                } else if ($tracing->final_test === 1) {
                    $tracing['final_test_name'] = 'Realizado';
                } else if ($tracing->final_test === 2) {
                    $tracing['final_test_name'] = 'No realizado';
                }
                if ($tracing->questionnaire === 0) {
                    $tracing['questionnaire_name'] = 'Pendiente';
                } else if ($tracing->questionnaire === 1) {
                    $tracing['questionnaire_name'] = 'Realizado';
                } else if ($tracing->questionnaire === 2) {
                    $tracing['questionnaire_name'] = 'No realizado';
                }
                $course = Course::where('id', $tracing->course_id)->first();
                $student = Student::where('id', $tracing->student_id)->first();
                $tracing['name'] = $course->group . '/' . $course->name . ' - ' . $student->name . ' ' . Carbon::parse($course->beginning)->format('d/m/Y') . ' - ' . Carbon::parse($course->end)->format('d/m/Y');
            }
            return response()->json([
                'status' => 200,
                'tracing' => $tracing
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Tracing::destroy($id);
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
     * CSV de la rentabildad
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function tracingsCSV(Request $request){
        try {
            $tracings = Tracing::tracing();
            if ($request->course) {
                $tracings = $tracings->where('courses.id', $request->course);
            }
            if ($request->company) {
                $tracings = $tracings->where('companies.id', $request->company);
            }
            if ($request->student) {
                $tracings = $tracings->where('students.id', 'LIKE', $request->student);
            }
            if ($request->status) {
                $tracings = $tracings->where('courses.course_status_id', 'LIKE', $request->status);
            }
            if ($request->beginning) {
                $tracings = $tracings->where('courses.beginning', '>=', $request->beginning);
            }
            if ($request->end) {
                $tracings = $tracings->where('courses.beginning', '<=', $request->end);
            }

            $tracings = $tracings->orderBy('tracings.id', 'desc')->get();

            $data = [];
            if (count($tracings) > 0) {
                foreach ($tracings as $tracing) {
                    $element = [
                        'Curso' => $tracing['course'],
                        'Empresa' => $tracing['company'],
                        'Alumno' => $tracing['student'],
                        'Estado' => $tracing['status'],
                        'Horas Realizadas' => $tracing['performed_hours'],
                        'Horas Totales' => $tracing['total_hours'],
                        'Actividades Realizadas' => $tracing['performed_activities'],
                        'Actividades Totales' => $tracing['number_activities'],
                        'Unidades Realizadas' => $tracing['performed_units'],
                        'Unidades Totales' => $tracing['number_units'],
                        'Fecha Seguimiento' => $tracing->follow_up_date ? \Carbon\Carbon::parse($tracing->follow_up_date)->format('d/m/Y') : '',
                        'Test Final' => $tracing['final_test'] == 0 ? 'Pendiente' : ($tracing['final_test'] == 1 ? 'Realizado' : 'No realizado'),
                        'Cuestionario' => $tracing['questionnaire'] == 0 ? 'Pendiente' : ($tracing['questionnaire'] == 1 ? 'Realizado' : 'No realizado'),
                        'Bienvenida' => $tracing['welcome_message'] == 1 ? 'Si' : 'No',
                        'Mensaje 25%' => $tracing['quarter_message'] == 1 ? 'Si' : 'No',
                        'Mensaje 50%' => $tracing['half_message'] == 1 ? 'Si' : 'No',
                        'Mensaje 75%' => $tracing['three_quarters_message'] == 1 ? 'Si' : 'No',
                        'Finalización' => $tracing['final_message'] == 1 ? 'Si' : 'No'
                    ];
                    $data[] = $element;
                }
            } else {
                $element = [
                    'Curso' => '',
                    'Empresa' => '',
                    'Alumno' => '',
                    'Estado' => '',
                    'Horas Realizadas' => '',
                    'Horas Totales' => '',
                    'Actividades Realizadas' => '',
                    'Actividades Totales' => '',
                    'Unidades Realizadas' => '',
                    'Unidades Totales' => '',
                    'Fecha Seguimiento' => '',
                    'Test Final' => '',
                    'Cuestionario' => '',
                    'Bienvenida' => '',
                    'Mensaje 25%' => '',
                    'Mensaje 50%' => '',
                    'Mensaje 75%' => '',
                    'Finalización' => ''
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

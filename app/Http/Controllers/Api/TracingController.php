<?php

namespace App\Http\Controllers\Api;
use App\Helpers\CalculationHelpers;
use App\Exports\TracingsExport;
use App\Helpers\GeneralHelpers;
use App\Helpers\MoodleHelpers;
use App\Http\Resources\TracingResource;
use App\Models\Course;
use App\Models\Student;
use App\Models\Tracing;
use App\Models\TrainingAction;
use App\Models\User;
use App\Models\WebPlatform;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TracingController extends BaseController
{
    /**
     * Obtener los seguimientos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = Tracing::tracing($mainCompanyId);

            $user = User::where('id', Auth::id())
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if ($user->teacher_id) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->teacher_id);
                });
            }
            $this->applyTracingFilters($query, $request);
            $this->applyCalendarDateFilter($query, $request);

            $sort = (string) $request->get('sort', 'follow_up_date');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            // campos directos (en tabla tracings)
            $direct = [
                'follow_up_date'        => 'tracings.follow_up_date',
                'final_test'            => 'tracings.final_test',
                'questionnaire'         => 'tracings.questionnaire',
                'welcome_message'       => 'tracings.welcome_message',
                'quarter_message'       => 'tracings.quarter_message',
                'half_message'          => 'tracings.half_message',
                'three_quarters_message'=> 'tracings.three_quarters_message',
                'final_message'         => 'tracings.final_message',
                // si tienes id/created_at:
                'id'                    => 'tracings.id',
                'created_at'            => 'tracings.created_at',
                'updated_at'            => 'tracings.updated_at',
            ];

            // ✅ subqueries para ordenar por relaciones (sin joins)
            switch ($key) {
                case 'company':
                    $query->orderBy(
                        DB::raw("(SELECT c.name FROM companies c WHERE c.id = tracings.company_id)"),
                        $dir
                    );
                    break;

                case 'student':
                    // apellido y luego nombre
                    $query->orderBy(
                        DB::raw("(SELECT s.surname FROM students s WHERE s.id = tracings.student_id)"),
                        $dir
                    )->orderBy(
                        DB::raw("(SELECT s.name FROM students s WHERE s.id = tracings.student_id)"),
                        $dir
                    );
                    break;

                case 'status':
                    // status del curso: courses.course_status_id -> course_statuses.name
                    $query->orderBy(
                        DB::raw("(
                    SELECT cs.name
                    FROM courses co
                    JOIN course_statuses cs ON cs.id = co.course_status_id
                    WHERE co.id = tracings.course_id
                )"),
                        $dir
                    );
                    break;

                case 'course':
                    // Orden “como lo que muestras”: formative_action / group + name
                    $query->orderBy(
                        DB::raw("(
                    SELECT CONCAT(ta.formative_action,' / ', co.`group`, ' ', ta.name)
                    FROM courses co
                    JOIN training_actions ta ON ta.id = co.training_action_id
                    WHERE co.id = tracings.course_id
                )"),
                        $dir
                    );
                    break;

                default:
                    if (isset($direct[$key])) {
                        $query->orderBy($direct[$key], $dir);
                    } else {
                        // fallback
                        $query->orderBy('tracings.follow_up_date', 'desc');
                    }
                    break;
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $tracings = TracingResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'tracings' => $tracings,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $tracings = TracingResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'tracings' => $tracings,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            \Log::error("Error in index method: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $tracing = Tracing::tracing($mainCompanyId)
            ->where('tracings.id', $id)
            ->first();

        if ($tracing) {
            $course = Course::where('id', $tracing->course_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $student = Student::where('id', $tracing->student_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $parts = explode(' - ', $course->name);
            $code = trim($parts[0]);
            $tracing['name'] = $course->group.'/'. $course->name .' - '. $student->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');

            $trainingAction = TrainingAction::where('id', $course->training_action_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if ($request->boolean('refresh_moodle') && $trainingAction && $trainingAction->web_platform_id) {
                $webPlatform = WebPlatform::where('id', $trainingAction->web_platform_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if ($webPlatform && $webPlatform->url && $webPlatform->token) {
                    $moodleCourse = MoodleHelpers::getCourseByShortname($code.'/'.$course->group, $webPlatform->url, $webPlatform->token);

                    if (!empty($moodleCourse) && isset($moodleCourse['id'])) {
                        $courseData = MoodleHelpers::getActivityCount($moodleCourse['id'], $webPlatform->url, $webPlatform->token);
                        if (empty($courseData['error'])) {
                            $tracing['number_activities'] = $courseData['assignmentCount'];
                            $tracing['number_units'] = $courseData['normalScormCount'];
                        }
                        //   $tracing['number_questions'] = $courseData['questionCount'];

                        $endTime = Carbon::parse($course->end);
                        $currentTime = Carbon::now();

                        $courseData = MoodleHelpers::getStudentCourseDetails($moodleCourse['id'], $student->user, $webPlatform->url, $webPlatform->token);
                        if (empty($courseData['error'])) {
                            $tracing['performed_activities'] = $courseData['finishedActivities'];
                            $tracing['last_connection'] = $courseData['lastAccess'];
                            $tracing['performed_units'] = $courseData['unitsViewed'];
                            $tracing['performed_hours'] = CalculationHelpers::timeStringToDecimal($courseData['totalTime']);
                            $tracing['final_test'] = $courseData['evaluationFinalDone'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2);
                            $tracing['questionnaire'] = $courseData['cuestionar'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2);
                        }

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
                }
            }

            return $this->sendResponse(
                [
                    'tracing' => $tracing,
                ],
                trans('Obtenido con éxito')
            );
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
    public function store(Request $request){
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
    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $tracing = Tracing::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$tracing) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Seguimiento no encontrado'
                ]);
            }

            $data = $request->all();
            $element = $tracing->updateWithService($data);
            $tracing = Tracing::tracing($mainCompanyId)
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
                $course = Course::where('id', $tracing->course_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                $student = Student::where('id', $tracing->student_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                $tracing['name'] = $course->group . '/' . $course->name . ' - ' . $student->name . ' ' . Carbon::parse($course->beginning)->format('d/m/Y') . ' - ' . Carbon::parse($course->end)->format('d/m/Y');
            }
            return $this->sendResponse(
                [
                    'tracing' => $tracing,
                ],
                trans('Guardado con éxito')
            );
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
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $tracing = Tracing::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$tracing) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Seguimiento no encontrado'
                    ]);
                }

                Tracing::destroy($id);
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $tracings = Tracing::tracing($mainCompanyId);
            $this->applyTracingFilters($tracings, $request);
            $this->applyCalendarDateFilter($tracings, $request);

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
            return $this->sendResponse(
                [
                    'tracings' => $data,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = Tracing::tracing($mainCompanyId);

            $user = User::where('id', Auth::id())
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if ($user && $user->teacher_id) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->teacher_id);
                });
            }

            $this->applyTracingFilters($query, $request);
            $this->applyCalendarDateFilter($query, $request);

            // ✅ mismo sort del index (si quieres respetarlo en el excel)
            $sort = (string) $request->get('sort', 'follow_up_date');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            $direct = [
                'follow_up_date'         => 'tracings.follow_up_date',
                'final_test'             => 'tracings.final_test',
                'questionnaire'          => 'tracings.questionnaire',
                'welcome_message'        => 'tracings.welcome_message',
                'quarter_message'        => 'tracings.quarter_message',
                'half_message'           => 'tracings.half_message',
                'three_quarters_message' => 'tracings.three_quarters_message',
                'final_message'          => 'tracings.final_message',
                'id'                     => 'tracings.id',
                'created_at'             => 'tracings.created_at',
                'updated_at'             => 'tracings.updated_at',
            ];

            switch ($key) {
                case 'company':
                    $query->orderByRaw("(SELECT c.name FROM companies c WHERE c.id = tracings.company_id) {$dir}");
                    break;

                case 'student':
                    $query->orderByRaw("(SELECT s.surname FROM students s WHERE s.id = tracings.student_id) {$dir}")
                        ->orderByRaw("(SELECT s.name FROM students s WHERE s.id = tracings.student_id) {$dir}");
                    break;

                case 'status':
                    $query->orderByRaw("(
                    SELECT cs.name
                    FROM courses co
                    JOIN course_statuses cs ON cs.id = co.course_status_id
                    WHERE co.id = tracings.course_id
                ) {$dir}");
                    break;

                case 'course':
                    $query->orderByRaw("(
                    SELECT CONCAT(ta.formative_action,' / ', co.`group`, ' ', ta.name)
                    FROM courses co
                    JOIN training_actions ta ON ta.id = co.training_action_id
                    WHERE co.id = tracings.course_id
                ) {$dir}");
                    break;

                default:
                    if (isset($direct[$key])) {
                        $query->orderBy($direct[$key], $dir);
                    } else {
                        $query->orderBy('tracings.follow_up_date', 'desc');
                    }
                    break;
            }

            $items = $query->get();

            // ✅ helpers
            $yesNo = fn($v) => ((string)$v === '1' || $v === 1 || $v === true) ? 'Sí' : 'No';

            $fmtDate = function ($v) {
                if (!$v) return '';
                try {
                    return Carbon::parse($v)->format('d-m-Y');
                } catch (\Exception $e) {
                    return '';
                }
            };

            // 👇 Mapea en el ORDEN EXACTO del excel plantilla Seguimientos.xlsx
            $rows = $items->map(function ($tr) use ($yesNo, $fmtDate) {

                $studentFull = trim(
                    (string) optional($tr->student)->name . ' ' .
                    (string) optional($tr->student)->surname
                );

                return [
                    // 📅 Fecha seguimiento
                    $fmtDate($tr->follow_up_date),

                    // 🏢 Empresa
                    optional($tr->company)->name ?? '',

                    // 👤 Alumno
                    $studentFull,

                    // 📚 Curso (YA calculado en el scope)
                    $tr->course->name ?? '',

                    // 🏷️ Tipo curso
                    optional(optional($tr->course)->courseType)->name ?? '',

                    // 📌 Estado curso
                    optional(optional($tr->course)->courseStatus)->name ?? '',

                    // 📩 Mensajes
                    $yesNo($tr->welcome_message),
                    $yesNo($tr->quarter_message),
                    $yesNo($tr->half_message),
                    $yesNo($tr->three_quarters_message),
                    $yesNo($tr->final_message),

                    // 📝 Seguimiento
                    $tr->questionnaire_name ?? '',
                    $tr->final_test_name ?? '',

                    // 🕒 Fechas sistema
                    $fmtDate($tr->created_at),
                    $fmtDate($tr->updated_at),
                ];
            });

            return Excel::download(new TracingsExport($rows), 'Seguimientos.xlsx');

        } catch (\Exception $e) {
            Log::error("Error exporting tracings excel: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function applyCalendarDateFilter($query, Request $request): void
    {
        $singleDate = $request->get('calendar_date');
        $from = $this->parseDateFilter($request->get('calendar_date_from') ?? $singleDate);
        $to = $this->parseDateFilter($request->get('calendar_date_to') ?? $singleDate);

        if (!$from && !$to) {
            return;
        }

        if (!$from) {
            $from = $to;
        }
        if (!$to) {
            $to = $from;
        }

        $tracingDateColumns = [
            'tracings.follow_up_date',
            'tracings.welcome_date_sent',
            'tracings.quarter_date_sent',
            'tracings.half_date_sent',
            'tracings.three_quarters_date_sent',
            'tracings.final_date_sent',
        ];

        $courseDateColumns = [
            'beginning',
            'welcome_date',
            'quarter_date',
            'half_date',
            'three_quarters_date',
            'final_date',
        ];

        $query->where(function ($dateQuery) use ($tracingDateColumns, $courseDateColumns, $from, $to) {
            foreach ($tracingDateColumns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $dateQuery->{$method}(function ($q) use ($column, $from, $to) {
                    $q->whereDate($column, '>=', $from)
                        ->whereDate($column, '<=', $to);
                });
            }

            $dateQuery->orWhereHas('course', function ($courseQuery) use ($courseDateColumns, $from, $to) {
                $courseQuery->where(function ($courseDateQuery) use ($courseDateColumns, $from, $to) {
                    foreach ($courseDateColumns as $index => $column) {
                        $method = $index === 0 ? 'where' : 'orWhere';
                        $courseDateQuery->{$method}(function ($q) use ($column, $from, $to) {
                            $q->whereDate($column, '>=', $from)
                                ->whereDate($column, '<=', $to);
                        });
                    }
                });
            });
        });
    }

    private function applyTracingFilters($query, Request $request): void
    {
        if ($request->filled('course')) {
            $query->where('tracings.course_id', $request->course);
        }

        if ($request->filled('company')) {
            $query->where('tracings.company_id', $request->company);
        }

        if ($request->filled('student')) {
            $query->where('tracings.student_id', $request->student);
        }

        if ($request->filled('status')) {
            $query->whereHas('course.courseStatus', function ($q) use ($request) {
                $q->where('id', $request->status);
            });
        }

        if ($request->filled('type')) {
            $query->whereHas('course.courseType', function ($q) use ($request) {
                $q->where('id', $request->type);
            });
        }

        if ($request->filled('beginning')) {
            $beginning = Carbon::parse($request->beginning)->format('Y-m-d');
            $query->whereHas('course', fn ($q) => $q->whereDate('beginning', '>=', $beginning));
        }

        if ($request->filled('end')) {
            $end = Carbon::parse($request->end)->format('Y-m-d');
            $query->whereHas('course', fn ($q) => $q->whereDate('beginning', '<=', $end));
        }
    }

    private function parseDateFilter($value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}

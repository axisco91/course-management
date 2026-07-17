<?php

namespace App\Http\Controllers\Api;

use App\Exports\CoursesExport;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CourseController extends BaseController
{
    public function index(Request $request) {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = Course::withCourseData($mainCompanyId);

            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $query->where('teacher_id', $user->teacher_id);
            } else if ($user->advisor_id) {
                $query->whereExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))
                        ->from('billings')
                        ->whereColumn('billings.course_id', 'courses.id')
                        ->where('billings.advisor_id', $user->advisor_id);
                });
            }


            if ($request->formative_action) {
                $query = $query->where('courses.name', 'like', '%'.$request->formative_action.'%');
            }
            if ($request->name) {
                $query = $query->where('courses.name', 'like', '%'.$request->name.'%');
            }
            if ($request->group) {
                $query = $query->where('courses.group', 'like', '%'.$request->group.'%');
            }
            if ($request->filled('start_date')) {
                $query->whereDate('courses.end', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('courses.beginning', '<=', $request->end_date);
            }
            if ($request->type) {
                $query = $query->where('course_type_id', $request->type);
            }
            if ($request->status) {
                $query = $query->where('course_status_id', $request->status);
            }
            if ($request->filled('modality') || $request->filled('modalities')) {
                $modalityId = $request->input('modality', $request->input('modalities'));
                $query->whereExists(function ($subQuery) use ($modalityId) {
                    $subQuery->selectRaw('1')
                        ->from('training_actions')
                        ->whereColumn('training_actions.id', 'courses.training_action_id')
                        ->where('training_actions.modality_id', $modalityId);
                });
            }
            if ($request->company) {
                $registrations = Registration::where('company_id', $request->company)->groupBy('course_id')->pluck('course_id')->toArray();
                $query = $query->where(function ($query) use ($registrations){
                    $query->WhereIn('courses.id', $registrations);
                });
            }

            $sort = (string) $request->get('sort', '-beginning'); // default: cursos más nuevos primero
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            /**
             * OJO:
             * - 'name' en tu grid en realidad muestra `label` (DB::raw concat).
             *   Si quieres ordenar “igual que lo que ves”, lo más estable es ordenar por:
             *   training_actions.formative_action + courses.group + training_actions.name
             *   (necesita join de training_actions)
             */

            $sortable = [
                'name' => null, // lo resolvemos manual con join
                'type' => 'course_types.name',
                'teacher' => 'teachers.surname', // o teachers.name si prefieres
                'beginning' => 'courses.beginning',
                'end' => 'courses.end',
                'registrations_count' => 'number_registrations', // lo resolvemos manual
                'status' => 'courses.course_status_id', // mejor por ID (o por course_statuses.name si quieres)
            ];

// joins según campo
            switch ($key) {
                case 'name':
                    $query->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');
                    // orden “parecido” al label visible
                    $query->orderBy('training_actions.formative_action', $dir)
                        ->orderBy('courses.group', $dir)
                        ->orderBy('training_actions.name', $dir);
                    break;

                case 'type':
                    $query->leftJoin('course_types', 'course_types.id', '=', 'courses.course_type_id');
                    $query->orderBy('course_types.name', $dir);
                    break;

                case 'teacher':
                    $query->leftJoin('teachers', 'teachers.id', '=', 'courses.teacher_id');
                    // orden por apellido y luego nombre
                    $query->orderBy('teachers.surname', $dir)->orderBy('teachers.name', $dir);
                    break;

                case 'registrations_count':
                    // si tu scope ya trae number_registrations como subquery alias:
                    // ORDER BY alias funciona en MySQL normalmente, pero con groupBy puede fallar.
                    // Usamos un subquery robusto:
                    $query->orderByRaw(
                        "(SELECT COUNT(*) FROM registrations r WHERE r.course_id = courses.id) {$dir}"
                    );
                    break;

                case 'status':
                    // si quieres por nombre del status (PENDIENTE/IMPARTICIÓN...) -> join course_statuses
                    // $query->leftJoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id');
                    // $query->orderBy('course_statuses.name', $dir);
                    $query->orderBy('courses.course_status_id', $dir);
                    break;

                default:
                    if (isset($sortable[$key]) && $sortable[$key]) {
                        $query->orderBy($sortable[$key], $dir);
                    } else {
                        $query->orderBy('courses.beginning', 'desc');
                    }
                    break;
            }

            // evita duplicados si metiste joins
            if (in_array($key, ['name', 'type', 'teacher', 'status'], true)) {
                $query->select('courses.*')->distinct();
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $courses = CourseResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'courses' => $courses,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $courses = CourseResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'courses' => $courses,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request){
        $request->validate([
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'web_platform_id' => ['nullable', 'integer', 'exists:web_platforms,id'],
        ]);

        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $course = Course::createWithService($data);

            $course = Course::withCourseData($mainCompanyId)->Where('courses.id', $course->id)->first();

            return $this->sendResponse(
                [
                    'course' => $course,
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id, Request $request){
        $request->validate([
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'web_platform_id' => ['nullable', 'integer', 'exists:web_platforms,id'],
        ]);

        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $course = Course::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$course) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Curso no encontrado'
                ]);
            }

            $course = $course->updateWithService($request->all());

            \Log::info('Updated course: ' . json_encode($course));
        } catch (\Exception $e){
            \Log::error('Error updating course: ' . $e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'chores' => Course::withCourseData($mainCompanyId)->Where('courses.id', $course->id)->first(),
            ],
            trans('Guardado con éxito')
        );
    }
    public function show($id, Request $request){
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $course = Course::withCourseData($mainCompanyId)
            ->where('courses.id', $id)
            ->first();

        if (!$course) {
            return response()->json([
                'status' => 404,
                'message' => 'Curso no encontrado'
            ]);
        }

        return $this->sendResponse(
            [
                'course' => $course,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
                $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $course = Course::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$course) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Curso no encontrado'
                    ]);
                }

                Course::destroy($id);
                return $this->sendResponse(
                    [

                    ],
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

    public function setData(Request $request) {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $course = Course::setName($request->id, $request->training_action_id, $mainCompanyId);

        return $this->sendResponse(
            [
                'course' => $course,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function getStudents($id, Request $request){
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return $this->sendResponse(
            [
                'registrations' => Student::getRegistrated($id, $mainCompanyId)->get(),
            ],
            trans('Obtenido con éxito')
        );
    }

    /**
     * Este método restablece las fechas de seguimiento futuras (tracings), y elimina las tareas (chores) y facturas (bills) asociadas a un curso si el curso está cancelado.
     *
     * Primero, busca el curso por su ID. Si el curso no se encuentra, devuelve una respuesta con un estado 404 y un mensaje indicando que el curso no se encontró.
     *
     * Si el curso se encuentra, verifica si el estado del curso es 'anulado' (course_status_id === 4). Si el curso no está anulado, devuelve una respuesta con un estado 400 y un mensaje indicando que el curso no está cancelado.
     *
     * Si el curso está cancelado, llama al método resetChoresAndFutureTracings del curso para restablecer las fechas de seguimiento futuras y eliminar las tareas y facturas asociadas. Luego, devuelve una respuesta con un estado 200 y un mensaje indicando que los seguimientos futuros se restablecieron con éxito.
     *
     * @param  int  $id  El ID del curso.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON con el estado y el mensaje.
     */
    public function resetTracingsIfCancelled($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        Log::info('Resetting tracings for course: ' . $id);
        $course = Course::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if ($course) {
            Log::info('Course found: ' . $id);
            Log::info('Course status id: ' . $course->course_status_id);
            if ($course->course_status_id === 4) {
                Log::info('Course status is cancelled. Resetting tracings...');
                $course->resetChoresAndFutureTracings();

                // Add this new log
                $remainingBills = Bill::where('course_id', $id)->count();
                Log::info("After resetting, {$remainingBills} bills remain for course {$id}");

                Log::info('Tracings reset successfully');

                return $this->sendResponse(
                    [],
                    trans('Seguimientos futuros restablecidos con éxito')
                );
            } else {
                Log::info('Course status is not cancelled');
                return response()->json([
                    'status' => 400,
                    'message' => 'El curso no está anulado'
                ]);
            }
        } else {
            Log::info('Course not found: ' . $id);
            return response()->json([
                'status' => 404,
                'message' => 'Curso no encontrado'
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            // ✅ query base (igual que index)
            $query = Course::query()->where('main_company_id', $mainCompanyId);

            $user = User::where('id', Auth::id())
                ->where('main_company_id', $mainCompanyId)
                ->first();

            // ✅ mismos permisos que index
            if ($user && $user->teacher_id) {
                $query->where('teacher_id', $user->teacher_id);
            } else if ($user && $user->advisor_id) {
                $query->whereExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))
                        ->from('billings')
                        ->whereColumn('billings.course_id', 'courses.id')
                        ->where('billings.advisor_id', $user->advisor_id);
                });
            }

            // ✅ filtros iguales al index
            if ($request->formative_action) {
                // en tu index filtras por courses.name
                $query->where('courses.name', 'like', '%' . $request->formative_action . '%');
            }
            if ($request->name) {
                $query->where('courses.name', 'like', '%' . $request->name . '%');
            }
            if ($request->group) {
                $query->where('courses.group', 'like', '%' . $request->group . '%');
            }
            if ($request->filled('start_date')) {
                $query->whereDate('courses.end', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('courses.beginning', '<=', $request->end_date);
            }
            if ($request->type) {
                $query->where('course_type_id', $request->type);
            }
            if ($request->status) {
                $query->where('course_status_id', $request->status);
            }
            if ($request->company) {
                $registrations = Registration::where('company_id', $request->company)
                    ->groupBy('course_id')
                    ->pluck('course_id')
                    ->toArray();

                $query->whereIn('courses.id', $registrations);
            }

            // ✅ joins/nombres (como haces con with en training actions)
            $query->with([
                'trainingAction:id,name,formative_action',
                'courseType:id,name',
                'teacher:id,name,surname',
                'courseStatus:id,name',
            ]);

            // ✅ contar alumnos (si quieres igual que el grid)
            $query->withCount(['registrations as number_registrations']);

            // ✅ orden opcional
            $query->orderBy('courses.beginning', 'desc');

            $items = $query->get();

            $rows = $items->map(function ($c) {
                $teacherFull = trim(
                    (string) data_get($c, 'teacher.surname', '') . ' ' . (string) data_get($c, 'teacher.name', '')
                );

                // 👇 keys EXACTAS = headings()
                return [
                    'Curso'          => $c->name ?? data_get($c, 'label', ''),
                    'Grupo'          => $c->group ?? '',
                    'Acción Formativa' => data_get($c, 'trainingAction.formative_action', ''),
                    'Tipo'           => data_get($c, 'courseType.name', ''),
                    'Profesor'       => $teacherFull,
                    'Fecha inicio'   => $c->beginning ?? '',
                    'Fecha fin'      => $c->end ?? '',
                    'Estado'         => data_get($c, 'courseStatus.name', ''),
                    'Nº alumnos'     => $c->number_registrations ?? 0,
                ];
            });

            return Excel::download(new CoursesExport($rows), 'Cursos.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function nextFormativeAction(Request $request, $trainingActionId)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            // 1) Buscar el máximo group (numérico) dentro de esa empresa + training_action
            //    group es string con ceros (001/0001/etc), por eso CAST a UNSIGNED
            $maxNumeric = Course::query()
                ->where('main_company_id', $mainCompanyId)
                ->where('training_action_id', $trainingActionId)
                ->selectRaw('MAX(CAST(`group` AS UNSIGNED)) as max_value')
                ->value('max_value');

            // 2) Si no hay cursos -> sugerimos 001 y dejamos editar
            if ($maxNumeric === null) {
                return $this->sendResponse(
                    [
                        'group' => '0001',
                        'editable' => true, // ✅ importante para tu caso
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // 3) Calcular siguiente
            $next = ((int) $maxNumeric) + 1;

            // 4) Formatear: mínimo 3 dígitos (001..999..1000)
            //    Si en tu BD usas 4 dígitos (0001), cámbialo a 4.
            $group = str_pad((string) $next, 4, '0', STR_PAD_LEFT);

            return $this->sendResponse(
                [
                    'group' => $group,
                    'editable' => false,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

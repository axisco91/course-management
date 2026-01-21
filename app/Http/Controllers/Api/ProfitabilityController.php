<?php

namespace App\Http\Controllers\Api;
use App\Exports\ProfitabilitiesExport;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CnaeResource;
use App\Http\Resources\ProfitabilityResource;
use App\Models\Company;
use App\Models\Course;
use App\Models\Profitability;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProfitabilityController extends BaseController
{

    /**
 * Obtener rentabilidad
 * @return \Illuminate\Http\JsonResponse
 */
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = Profitability::query()
                ->where('profitabilities.main_company_id', $mainCompanyId)
                ->with([
                    'company:id,name',
                    'course:id,training_action_id,course_status_id,beginning',
                    'course.trainingAction:id,formative_action,name',
                    'course.courseStatus:id,name',
                    'student:id,name,surname',
                    'registrations.student:id,name,surname',
                ]);

            // ✅ filtros por IDs
            if ($request->filled('course')) {
                $query->where('profitabilities.course_id', (int) $request->course);
            }

            if ($request->filled('company')) {
                $query->where('profitabilities.company_id', (int) $request->company);
            }

            // ✅ status: correcto con whereHas (sin join)
            if ($request->filled('status')) {
                $query->whereHas('course', function ($q) use ($request) {
                    $q->where('course_status_id', (int) $request->status);
                });
            }

            // ✅ order by course.beginning (subquery)
            $sort = (string) $request->get('sort', 'course');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            switch ($key) {

                case 'course':
                    // ordenar por: formative_action / group + name
                    $query->orderBy(
                        Course::selectRaw("CONCAT(training_actions.formative_action,' / ', courses.`group`, ' ', training_actions.name)")
                            ->join('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
                            ->whereColumn('courses.id', 'profitabilities.course_id')
                            ->limit(1),
                        $dir
                    );
                    break;

                case 'company':
                    $query->orderBy(
                        Company::select('name')
                            ->whereColumn('companies.id', 'profitabilities.company_id')
                            ->limit(1),
                        $dir
                    );
                    break;

                case 'year':
                    // año sacado de course.beginning
                    $query->orderBy(
                        Course::selectRaw('YEAR(beginning)')
                            ->whereColumn('courses.id', 'profitabilities.course_id')
                            ->limit(1),
                        $dir
                    );
                    break;

                default:
                    // fallback seguro
                    $query->orderBy(
                        Course::select('beginning')
                            ->whereColumn('courses.id', 'profitabilities.course_id')
                            ->limit(1),
                        'desc'
                    );
                    break;
            }

            if ($request->filled('perPage')) {
                $paginator = $query->paginate((int) $request->perPage);

                $profitabilities = ProfitabilityResource::collection($paginator);
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'profits' => $profitabilities,
                        'links' => $paginationData['links'],
                        'meta' => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            return $this->sendResponse(
                [
                    'profits' => ProfitabilityResource::collection($query->get()),
                ],
                trans('Obtenido con éxito')
            );

        } catch (\Exception $e) {
            \Log::error("Profitability index error: ".$e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $profitability = Profitability::profitability($mainCompanyId)
            ->where('profitabilities.id', $id)
            ->first();
        if ($profitability) {
            $course = Course::where('id', $profitability->course_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if ($course) {
                $beginning = Carbon::parse($course->beginning)->format('d/m/Y');
                $end = Carbon::parse($course->end)->format('d/m/Y');
                $profitability['name'] = $course->group.'/'. $course->name .' '.$beginning.' - '.$end;
                return $this->sendResponse(
                    [
                        'profit' => $profitability,
                    ],
                    trans('Obtenido con éxito')
                );
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
    public function store(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $profitability = Profitability::createWithService($data);

            $profitability = Profitability::profitability($mainCompanyId)
                ->where('profitabilities.id', $profitability->id)
                ->first();

            return $this->sendResponse(
                [
                    'profit' => $profitability,
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

    /**
     * Editar rentabbilidad
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $profitability = Profitability::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$profitability) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Profitabilidad no existe'
                ]);
            }

            $data = $request->all();

            $profitability->updateWithService($data);

            return $this->sendResponse(
                [
                    'profit' => $profitability,
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

                $profitability = Profitability::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$profitability) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Profitabilidad no existe'
                    ]);
                }

                Profitability::destroy($id);
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
     * Obtenemos los alumnos que están en esa rentabilidad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudents($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $profitability = Profitability::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if (!$profitability) {
            return response()->json([
                'status' => 404,
                'message' => 'Profitabilidad no existe'
            ]);
        }

        $registrations = $profitability->registrations()->get()->pluck('student_id')->toArray();

        $students = Student::select('students.*', 'companies.name as company_name')
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->whereIn('students.id', $registrations)
            ->FilterMainCompany($mainCompanyId)
            ->get();
        return $this->sendResponse(
            [
                'students' => $students,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function exportExcel(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = Profitability::query()
                ->where('profitabilities.main_company_id', $mainCompanyId)
                ->with([
                    'company:id,name',
                    // ✅ group SIN backticks
                    'course:id,training_action_id,course_status_id,beginning,group,course_type_id,teacher_id',
                    'course.trainingAction:id,formative_action,name,total_hours',
                    'course.courseStatus:id,name',
                    // alumno directo (si existe)
                    'student:id,name,surname',
                    // varios alumnos
                    'registrations.student:id,name,surname',
                    // si tienes teacher como relación en Course, mejor:
                    // 'course.teacher:id,name,surname',
                ]);

            // =======================
            // ✅ FILTROS (como tu UI)
            // =======================
            if ($request->filled('course')) {
                $query->where('profitabilities.course_id', $request->get('course'));
            }

            if ($request->filled('company')) {
                $query->where('profitabilities.company_id', $request->get('company'));
            }

            if ($request->filled('status')) {
                $status = $request->get('status');
                // ✅ SIN join: filtra por relación
                $query->whereHas('course', fn ($q) => $q->where('course_status_id', $status));
            }

            // =======================
            // ✅ SORT (course/company/year)
            // tu frontend manda sortColumn y sort asc/desc:
            // sort: 'course' | 'company' | 'year'
            // =======================
            $sort = (string) $request->get('sort', 'course'); // default si quieres
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            switch ($key) {
                case 'company':
                    $query->orderBy(
                        DB::raw("(SELECT c.name FROM companies c WHERE c.id = profitabilities.company_id)"),
                        $dir
                    );
                    break;

                case 'year':
                    // si tienes columna year en profitabilities, usa esa:
                    // $query->orderBy('profitabilities.year', $dir);

                    // si NO tienes year, ordena por beginning del curso:
                    $query->orderBy(
                        Course::select('beginning')
                            ->whereColumn('courses.id', 'profitabilities.course_id')
                            ->limit(1),
                        $dir
                    );
                    break;

                case 'course':
                default:
                    // orden “como lo que ves” (formative_action / group + name)
                    $query->orderBy(
                        DB::raw("(
                        SELECT CONCAT(ta.formative_action,' / ', co.`group`, ' ', ta.name)
                        FROM courses co
                        JOIN training_actions ta ON ta.id = co.training_action_id
                        WHERE co.id = profitabilities.course_id
                    )"),
                        $dir
                    );
                    break;
            }

            $items = $query->get();

            // =======================
            // ✅ HELPERS
            // =======================
            $num = function ($v) {
                if ($v === null || $v === '') return '';
                $n = (float) $v;

                return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
            };

            $percent = function ($v) use ($num) {
                if ($v === null || $v === '') return '';
                // si ya viene "12.34%" lo dejamos
                if (is_string($v) && str_contains($v, '%')) return $v;

                return $num($v) . '%';
            };

            $courseLabel = function ($p) {
                $taForm = data_get($p, 'course.trainingAction.formative_action', '');
                $taName = data_get($p, 'course.trainingAction.name', '');
                $group  = data_get($p, 'course.group', '');

                $label = trim($taForm . ' / ' . $group . ' ' . $taName);

                return $label !== '/  ' ? $label : trim($taName);
            };

            $studentsLabel = function ($p) {
                // ✅ varios alumnos (registrations)
                $regs = data_get($p, 'registrations', []);
                if (is_iterable($regs)) {
                    $names = [];
                    foreach ($regs as $r) {
                        $n = trim((string) data_get($r, 'student.name', '') . ' ' . (string) data_get($r, 'student.surname', ''));
                        if ($n !== '') $names[] = $n;
                    }
                    if (count($names) > 0) return implode(', ', $names);
                }

                // fallback: student directo si existe
                $one = trim((string) data_get($p, 'student.name', '') . ' ' . (string) data_get($p, 'student.surname', ''));

                return $one;
            };

            // =======================
            // ✅ ROWS (orden EXACTO del Excel)
            // =======================
            $rows = $items->map(function ($p) use ($num, $percent, $courseLabel, $studentsLabel) {

                // Año: si tienes profitabilities.year úsalo; si no, sácalo de course.beginning
                $year = data_get($p, 'year', null);
                if (!$year) {
                    $b = data_get($p, 'course.beginning', null);
                    $year = $b ? Carbon::parse($b)->format('Y') : '';
                }

                // Ajusta estos campos a tus columnas reales en profitabilities
                $precio                = data_get($p, 'price', data_get($p, 'precio', ''));
                $licencia              = data_get($p, 'license', data_get($p, 'licencia', ''));
                $docente               = data_get($p, 'teacher', data_get($p, 'docente', ''));
                $gestion               = data_get($p, 'management', data_get($p, 'gestion', ''));
                $tituloNebrija         = data_get($p, 'nebrija_title', data_get($p, 'titulo_nebrija', ''));
                $descuento             = data_get($p, 'discount', data_get($p, 'descuento', ''));
                $comisionColaborador   = data_get($p, 'collaborator_commission', data_get($p, 'comision_colaborador', ''));
                $comisionAsesoria      = data_get($p, 'advisor_commission', data_get($p, 'comision_asesoria', ''));
                $total                 = data_get($p, 'total', '');
                $beneficio             = data_get($p, 'benefits', data_get($p, 'beneficio', ''));
                $rentabilidad          = data_get($p, 'profitability', data_get($p, 'rentabilidad', ''));

                return [
                    $courseLabel($p),                         // Curso
                    (string) $year,                           // Año
                    (string) data_get($p, 'company.name', ''),// Empresa
                    $studentsLabel($p),                       // Alumnos (varios)

                    $num($precio),                            // Precio
                    $num($licencia),                          // Licencia
                    $num($docente),                           // Docente
                    $num($gestion),                           // Gestión
                    $num($tituloNebrija),                     // Titulo Nebrija
                    $num($descuento),                         // Descuento
                    $num($comisionColaborador),               // Comisión Colaborador
                    $num($comisionAsesoria),                  // Comisión Asesoría
                    $num($total),                             // Total
                    $num($beneficio),                         // Beneficio
                    $percent($rentabilidad),                  // Rentabilidad
                ];
            });

            return Excel::download(
                new ProfitabilitiesExport($rows),
                'Rentabilidad.xlsx'
            );

        } catch (\Exception $e) {
            \Log::error('Profitability exportExcel error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

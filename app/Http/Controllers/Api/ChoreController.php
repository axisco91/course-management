<?php

namespace App\Http\Controllers\Api;
use App\Exports\ChoresExport;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ChoreResource;
use App\Models\Chore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ChoreController extends BaseController
{

    /**
     * Obtenemos tareas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = Chore::chore($mainCompanyId);

            $user = User::find(Auth::id());

            if ($user->teacher_id) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->teacher_id);
                });
            }

            if ($request->course) {
                $query->where('course_id', $request->course);
                // o si quieres asegurarte que existe:
                // $query->whereHas('course', fn($q)=>$q->where('id',$request->course));
            }

            if ($request->company) {
                $companyId = $request->company;

                $query->where(function ($q) use ($companyId, $mainCompanyId) {
                    $q->where('chores.company_id', $companyId)
                        ->orWhereExists(function ($subQuery) use ($companyId, $mainCompanyId) {
                            $subQuery->selectRaw('1')
                                ->from('registrations')
                                ->whereColumn('registrations.chore_id', 'chores.id')
                                ->where('registrations.company_id', $companyId)
                                ->where('registrations.main_company_id', $mainCompanyId);
                        });
                });
            }

            if ($request->student) {
                $query->where('student_id', $request->student);
            }

            if ($request->status) {
                $query->whereHas('course.courseStatus', function ($q) use ($request) {
                    $q->where('id', $request->status);
                });
            }

            if ($request->beginning) {
                $beginning = \Illuminate\Support\Carbon::parse($request->beginning)->format('Y-m-d');
                $query->whereHas('course', fn ($q) => $q->whereDate('beginning', '>=', $beginning));
            }

            if ($request->end) {
                $end = \Illuminate\Support\Carbon::parse($request->end)->format('Y-m-d');
                $query->whereHas('course', fn ($q) => $q->whereDate('beginning', '<=', $end));
            }

            if ($request->type) {
                $query->whereHas('course.courseType', function ($q) use ($request) {
                    $q->where('id', $request->type);
                });
            }

            $query
                ->leftJoin('courses', 'courses.id', '=', 'chores.course_id')
                ->leftJoin('companies', 'companies.id', '=', 'chores.company_id')
                ->leftJoin('students', 'students.id', '=', 'chores.student_id')
                ->select('chores.*');

            $sortRaw = $request->get('sort', '-id');

            $dir = 'asc';
            $field = $sortRaw;

            if (is_string($sortRaw) && $sortRaw[0] === '-') {
                $dir = 'desc';
                $field = substr($sortRaw, 1);
            }

            $sortable = [
                'id' => 'chores.id',
                'course' => 'courses.name',
                'company' => 'companies.name',
                'student' => 'students.name',
            ];

            if (!array_key_exists($field, $sortable)) {
                $field = 'id';
            }

            // student = nombre + apellido
            if ($field === 'student') {
                $query
                    ->orderBy('students.name', $dir)
                    ->orderBy('students.surname', $dir);
            } else {
                $query->orderBy($sortable[$field], $dir);
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;
                $paginator = $query->paginate($perPage);

                return $this->sendResponse(
                    [
                        'chores' => ChoreResource::collection($paginator),
                        'links'  => GeneralHelpers::generatePaginationData($paginator)['links'],
                        'meta'   => GeneralHelpers::generatePaginationData($paginator)['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            return $this->sendResponse(
                ['chores' => ChoreResource::collection($query->get())],
                trans('Obtenido con éxito')
            );

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Obtener tarea
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $chore = Chore::chore($mainCompanyId)
            ->where('chores.id', $id)
            ->first();
        if ($chore) {

            return $this->sendResponse(
                [
                    'chore' => $chore,
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
     * Editamos tarea
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $chore = Chore::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if ($chore) {
                $data = $request->all();
                $element = $chore->updateWithService($data);
                $chore = Chore::chore($mainCompanyId)
                    ->where('chores.id', $element->id)
                    ->first();
                if ($chore) {

                    return $this->sendResponse(
                        [
                            'chore' => $chore,
                        ],
                        trans('Obtenido con éxito')
                    );
                }
                return response()->json([
                    'status' => 404,
                    'message' => 'Tarea no encontrada'
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminamos tarea
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $chore = Chore::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$chore) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Tarea no encontrada'
                    ]);
                }
                Chore::destroy($id);
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

    public function exportExcel(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = Chore::chore($mainCompanyId);

            $user = User::find(Auth::id());

            if ($user && $user->teacher_id) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->teacher_id);
                });
            }

            // ✅ filtros IGUALES a index
            if ($request->course) {
                $query->where('course_id', $request->course);
            }

            if ($request->company) {
                $companyId = $request->company;

                $query->where(function ($q) use ($companyId, $mainCompanyId) {
                    $q->where('chores.company_id', $companyId)
                        ->orWhereExists(function ($subQuery) use ($companyId, $mainCompanyId) {
                            $subQuery->selectRaw('1')
                                ->from('registrations')
                                ->whereColumn('registrations.chore_id', 'chores.id')
                                ->where('registrations.company_id', $companyId)
                                ->where('registrations.main_company_id', $mainCompanyId);
                        });
                });
            }

            if ($request->student) {
                $query->where('student_id', $request->student);
            }

            if ($request->status) {
                $query->whereHas('course.courseStatus', function ($q) use ($request) {
                    $q->where('id', $request->status);
                });
            }

            if ($request->beginning) {
                $beginning = Carbon::parse($request->beginning)->format('Y-m-d');
                $query->whereHas('course', fn ($q) => $q->whereDate('beginning', '>=', $beginning));
            }

            if ($request->end) {
                $end = Carbon::parse($request->end)->format('Y-m-d');
                $query->whereHas('course', fn ($q) => $q->whereDate('beginning', '<=', $end));
            }

            if ($request->type) {
                $query->whereHas('course.courseType', function ($q) use ($request) {
                    $q->where('id', $request->type);
                });
            }

            $query->orderByDesc('id');

            // ✅ para evitar N+1 en el map (ajusta nombres si difieren)
            $query->with([
                'course:id,name,group,beginning,end,course_type_id,course_status_id,teacher_id',
                'course.courseType:id,name',
                'course.courseStatus:id,name',
                'company:id,name',
                'student:id,name,surname',
            ]);

            $query
                ->leftJoin('courses', 'courses.id', '=', 'chores.course_id')
                ->leftJoin('companies', 'companies.id', '=', 'chores.company_id')
                ->leftJoin('students', 'students.id', '=', 'chores.student_id')
                ->select('chores.*');

            $sortRaw = $request->get('sort', '-id');

            $dir = 'asc';
            $field = $sortRaw;

            if (is_string($sortRaw) && $sortRaw[0] === '-') {
                $dir = 'desc';
                $field = substr($sortRaw, 1);
            }

            $sortable = [
                'id' => 'chores.id',
                'course' => 'courses.name',
                'company' => 'companies.name',
                'student' => 'students.name',
            ];

            if (!array_key_exists($field, $sortable)) {
                $field = 'id';
            }

            // student = nombre + apellido
            if ($field === 'student') {
                $query
                    ->orderBy('students.name', $dir)
                    ->orderBy('students.surname', $dir);
            } else {
                $query->orderBy($sortable[$field], $dir);
            }

            $items = $query->get();

            // ✅ helpers
            $fmtDate = function ($v) {
                if (!$v) return '';
                try {
                    return Carbon::parse($v)->format('d-m-Y');
                } catch (\Exception $e) {
                    return '';
                }
            };

            // 👇 aquí mapeas EXACTO a las columnas de tu plantilla Tareas.xlsx
            $rows = $items->map(function ($ch) use ($fmtDate) {
                $student = trim(
                    (string) data_get($ch, 'student.name', '') . ' ' .
                    (string) data_get($ch, 'student.surname', '')
                );

                return [
                    (string) data_get($ch, 'id', ''),
                    (string) data_get($ch, 'company.name', ''),
                    $student,

                    // curso
                    (string) data_get($ch, 'course.name', ''),
                    (string) data_get($ch, 'course.group', ''),
                    (string) data_get($ch, 'course.courseType.name', ''),
                    (string) data_get($ch, 'course.courseStatus.name', ''),
                    $fmtDate(data_get($ch, 'course.beginning')),
                    $fmtDate(data_get($ch, 'course.end')),

                    // tarea
                    (string) data_get($ch, 'title', data_get($ch, 'name', '')),
                    (string) data_get($ch, 'description', ''),
                    (string) data_get($ch, 'status', ''),

                    // fechas tarea (si existen)
                    $fmtDate(data_get($ch, 'beginning')),
                    $fmtDate(data_get($ch, 'end')),
                    $fmtDate(data_get($ch, 'created_at')),
                ];
            });

            return Excel::download(new ChoresExport($rows), 'Tareas.xlsx');

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
}

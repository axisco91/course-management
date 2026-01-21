<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Requests\TeacherRequests;
use App\Http\Resources\TeacherResource;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// ✅ Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeachersExport;

class TeacherController extends BaseController
{
    /**
     * ✅ Aplicar filtros (MISMO comportamiento que index)
     */
    private function applyTeacherFilters($query, Request $request)
    {
        if ($request->name) {
            $query->where('teachers.name', 'like', '%' . $request->name . '%');
        }
        if ($request->surname) {
            $query->where('teachers.surname', 'like', '%' . $request->surname . '%');
        }
        if ($request->dni) {
            $query->where('teachers.dni', 'like', '%' . $request->dni . '%');
        }
        if ($request->telephone) {
            $query->where('teachers.telephone', 'like', '%' . $request->telephone . '%');
        }
        if ($request->email) {
            $query->where('teachers.email', 'like', '%' . $request->email . '%');
        }
        if ($request->show_inactive == 'false') {
            $query->where('teachers.active', 1);
        }
        if ($request->area) {
            $query->whereHas('teacherAreas', function ($q) use ($request) {
                $q->where('teacher_areas.id', $request->area);
            });
        }

        return $query;
    }

    /**
     * Obtener docentes
     */
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = Teacher::teacher($mainCompanyId);

            // ✅ filtros
            $query = $this->applyTeacherFilters($query, $request);

            // ✅ sort
            $sort = (string) $request->get('sort', '-id');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            $sortable = [
                'id'        => 'teachers.id',
                'dni'       => 'teachers.dni',
                'telephone' => 'teachers.telephone',
                'email'     => 'teachers.email',
                'status'    => 'teachers.active',
            ];

            if ($key === 'name') {
                $query->orderBy('teachers.surname', $dir)->orderBy('teachers.name', $dir);
            } elseif (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir);
            } else {
                $query->orderBy('teachers.id', 'desc');
            }

            // ✅ paginación
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;
                $paginator = $query->paginate($perPage);

                $teachers = TeacherResource::collection($paginator);
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'teachers' => $teachers,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // sin paginación
            $teachers = TeacherResource::collection($query->get());

            return $this->sendResponse(
                [
                    'teachers' => $teachers,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener docente
     */
    public function show($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $teacher = Teacher::teacher($mainCompanyId)
            ->where('teachers.id', $id)
            ->first();

        if ($teacher) {
            $course = Course::where('teacher_id', $teacher->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $teacher['used'] = (bool) $course;

            $teacher['teacher_areas'] = $teacher->teacherAreas()
                ->select('id as value', 'name as label')
                ->get()
                ->toArray();

            return $this->sendResponse(
                [
                    'teacher' => $teacher,
                ],
                trans('Obtenido con éxito')
            );
        }

        return response()->json([
            'status' => 400,
            'message' => 'Docente no existe'
        ], 400);
    }

    /**
     * Crear docente
     */
    public function store(TeacherRequests $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $element = Teacher::createWithService($data);

            $teacher = Teacher::teacher($mainCompanyId)
                ->where('teachers.id', $element->id)
                ->first();

            return $this->sendResponse(
                [
                    'teacher' => $teacher,
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Editar docente
     */
    public function update($id, TeacherRequests $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();

            $teacher = Teacher::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$teacher) {
                Log::error("Teacher not found with id: $id");
                return response()->json([
                    'status' => 404,
                    'message' => 'Teacher not found'
                ], 404);
            }

            $teacher->updateWithService($data);

            return $this->sendResponse(
                [],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            Log::error("Exception occurred: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Comprobamos DNI
     */
    public function checkDni(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $teacher = Teacher::where('dni', $request->dni)
            ->FilterMainCompany($mainCompanyId);

        if ($request->id) {
            $teacher->where('id', '!=', $request->id);
        }

        $teacher = $teacher->first();

        return response()->json([
            'exists' => (bool) $teacher
        ]);
    }

    /**
     * Obtener cursos de docente
     */
    public function getTeachersCourses($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $courses = Course::teacherCourses($id, $mainCompanyId)->get();

        if (count($courses) > 0) {
            foreach ($courses as $course) {
                $course['beginning'] = Carbon::parse($course['beginning'])->format('d/m/Y');
                $course['end'] = Carbon::parse($course['end'])->format('d/m/Y');
            }
        }

        return $this->sendResponse(
            [
                'courses' => $courses,
            ],
            trans('Obtenido con éxito')
        );
    }

    /**
     * ✅ EXPORT EXCEL (Docentes)
     * Columnas: (según tu Docentes.xlsx)
     * Nombre, Apellidos, DNI, Correo, Teléfono, Usuario, Contraseña, Dirección, Código postal, Provincia,
     * Población, Iban, Observaciones, Estado
     */
    public function teachersExportExcel(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $query = Teacher::teacher($mainCompanyId);
        $query = $this->applyTeacherFilters($query, $request);

        $teachers = $query->orderBy('teachers.name', 'asc')->get();

        $rows = $teachers->map(function ($teacher) {
            $status = ((int)($teacher->active ?? 0) === 1) ? 'Activo' : 'Inactivo';

            // ⚠️ NO recomendado exportar contraseñas reales
            $passwordExport = ''; // (string)($teacher->password ?? '');

            // ✅ filas numéricas para evitar "Excel repair"
            return [
                (string)($teacher->name ?? ''),
                (string)($teacher->surname ?? ''),
                (string)($teacher->dni ?? ''),
                (string)($teacher->email ?? ''),
                (string)($teacher->telephone ?? ''),
                (string)($teacher->user ?? ''),
                (string)($passwordExport),
                (string)($teacher->address ?? ''),
                (string)($teacher->post_code ?? ''),
                (string)($teacher->province ? $teacher->province->name : ''),      // depende de tu scope Teacher::teacher()
                (string)($teacher->population ?? ''),
                (string)($teacher->iban ?? ''),
                (string)($teacher->observations ?? ''),
                (string)($status),
            ];
        });

        $fileName = 'docentes_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new TeachersExport($rows), $fileName);
    }

    /**
     * Eliminar docente
     */
    public function destroy($id, Request $request)
    {
        if ($id) {
            try {
                $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                // ✅ FIX: where('id', $id)
                $teacher = Teacher::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$teacher) {
                    Log::error("Teacher not found with id: $id");
                    return response()->json([
                        'status' => 404,
                        'message' => 'Teacher not found'
                    ], 404);
                }

                Teacher::destroy($id);

                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
    }
}

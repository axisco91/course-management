<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Requests\StudentRequests;
use App\Http\Resources\StudentResource;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ✅ Si vas a exportar Excel con Maatwebsite (recomendado):
// composer require maatwebsite/excel
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends BaseController
{
    /**
     * ✅ Aplica EXACTAMENTE los mismos filtros que el index/export.
     * Ojo: aquí asumimos que students tiene company_id.
     * Si tu query Student::student($mainCompanyId) ya hace joins, no pasa nada.
     */
    private function applyStudentFilters($query, Request $request)
    {
        $user = User::find(Auth::id());

        // Si el usuario es profesor, filtra por sus cursos
        if ($user && $user->teacher_id) {
            $query = $query->leftJoin('registrations', 'registrations.student_id', '=', 'students.id')
                ->leftJoin('courses', 'courses.id', '=', 'registrations.course_id')
                ->where('courses.teacher_id', $user->teacher_id);
        }

        // show_inactive viene del front como 'true' / 'false'
        if ($request->show_inactive === 'false') {
            $query = $query->where('students.active', 1);
        }

        if ($request->filled('name')) {
            $query = $query->where('students.name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('surname')) {
            $query = $query->where('students.surname', 'like', '%' . $request->surname . '%');
        }
        if ($request->filled('dni')) {
            $query = $query->where('students.dni', 'like', '%' . $request->dni . '%');
        }
        if ($request->filled('telephone')) {
            $query = $query->where('students.telephone', 'like', '%' . $request->telephone . '%');
        }
        if ($request->filled('email')) {
            $query = $query->where('students.email', 'like', '%' . $request->email . '%');
        }

        // ✅ company_id del filtro (0 = "All the companies")
        if ($request->filled('company_id') && (int)$request->company_id > 0) {
            $query = $query->where('students.company_id', (int)$request->company_id);
        }

        return $query;
    }

    /**
     * Obtener alumnos
     */
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = Student::student($mainCompanyId);

            // ✅ filtros unificados
            $query = $this->applyStudentFilters($query, $request);

            // ✅ groupBy (si tu query tiene joins)
            $query = $query->groupBy('students.id', 'students.name');

            // ✅ SORT (DataGrid manda: name | -name | dni | -dni | telephone | -telephone | email | -email | company | -company | status | -status)
            $sort = (string) $request->get('sort', '-id');
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            $sortable = [
                'id'        => 'students.id',
                'name'      => 'students.name',
                'dni'       => 'students.dni',
                'telephone' => 'students.telephone',
                'email'     => 'students.email',
                'status'    => 'students.active',
            ];

            if ($key === 'name') {
                $query->orderBy('students.surname', $dir)->orderBy('students.name', $dir);
            } elseif ($key === 'company') {
                // si Student::student() ya hace join con companies, esto funciona
                $query->orderBy('companies.name', $dir);
            } elseif (isset($sortable[$key])) {
                $query->orderBy($sortable[$key], $dir);
            } else {
                $query->orderBy('students.id', 'desc');
            }

            // ✅ paginación opcional
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $students = StudentResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'students' => $students,
                        'links'    => $paginationData['links'],
                        'meta'     => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $students = StudentResource::collection($query->get());

            return $this->sendResponse(
                [
                    'students' => $students,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener alumno
     */
    public function show($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $student = Student::student($mainCompanyId)
            ->where('students.id', $id)
            ->first();

        if ($student) {
            $registered = Registration::where('student_id', $student->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $student['used'] = (bool) $registered;

            return $this->sendResponse(
                [
                    'student' => $student,
                ],
                trans('Obtenido con éxito')
            );
        }

        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    /**
     * Creamos alumno
     */
    public function store(StudentRequests $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $student = Student::createWithService($data);

            return $this->sendResponse(
                [
                    'student' => $student
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editar alumno
     */
    public function update($id, StudentRequests $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();

            $student = Student::where('id', $id)->FilterMainCompany($mainCompanyId)->first();
            if (!$student) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Alumno no encontrado'
                ]);
            }

            $student->updateWithService($data);

            return $this->sendResponse(
                [],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Comprobamos si existe alumno con ese DNI
     */
    public function checkDni(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $student = Student::where('dni', $request['dni'])
            ->FilterMainCompany($mainCompanyId);

        if ($request['id']) {
            $student = $student->where('id', '!=', $request['id']);
        }

        $student = $student->first();

        return response()->json([
            'exists' => (bool) $student
        ]);
    }

    /**
     * Obtener los cursos del alumno
     */
    public function getStudentsCourses($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $registrations = Registration::studentCourses($id, $mainCompanyId)->get();

        if (count($registrations) > 0) {
            foreach ($registrations as $registration) {
                $registration['beginning'] = Carbon::parse($registration['beginning'])->format('d/m/Y');
                $registration['end'] = Carbon::parse($registration['end'])->format('d/m/Y');
            }
        }

        return $this->sendResponse(
            [
                'registrations' => $registrations,
            ],
            trans('Guardado con éxito')
        );
    }

    /**
     * Eliminar Alumno
     */
    public function destroy($id, Request $request)
    {
        if ($id) {
            try {
                $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $student = Student::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$student) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Alumno no encontrado'
                    ]);
                }

                Student::destroy($id);

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
     * ✅ EXPORT EXCEL (misma lógica de filtros que index)
     * Ruta recomendada: GET /students/export/excel
     */
    public function studentsExportExcel(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $query = Student::student($mainCompanyId);
        $query = $this->applyStudentFilters($query, $request);

        $students = $query->orderBy('students.name', 'asc')->get();

        $rows = $students->map(function ($student) {
            $disabled = ((int)($student->disabled ?? 0) === 1) ? 'Si' : 'No';
            $status   = ((int)($student->active ?? 0) === 1) ? 'Activo' : 'Inactivo';

            // Fecha Nacimiento en d/m/Y (si viene YYYY-MM-DD)
            $dob = '';
            if (!empty($student->date_of_birth)) {
                try {
                    $dob = \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y');
                } catch (\Throwable $e) {
                    $dob = (string)$student->date_of_birth;
                }
            }

            // ⚠️ CONTRASEÑA: NO recomendable exportarla. Dejo vacío para cuadrar columnas.
            $passwordExport = ''; // (string)($student->password ?? '');

            // IMPORTANTÍSIMO: devuelve ARRAY NUMÉRICO (no asociativo) para evitar “Excel repair”
            return [
                (string)($student->name ?? ''),
                (string)($student->surname ?? ''),
                (string)($student->dni ?? ''),
                (string)($student->email ?? ''),
                (string)($student->telephone ?? ''),
                (string)($student->company ?? ''),               // viene del scope Student::student()
                (string)($student->user ?? ''),
                (string)($passwordExport),
                (string)($dob),
                (string)($student->level_study ?? ''),           // “Nivel de Estudio”
                (string)($disabled),
                (string)($student->social_security_number ?? ''),
                (string)($student->c_quote ?? ''),
                (string)($student->quote_group ?? ''),
                (string)($student->professional_category ?? ''),
                (string)($student->annual_gross_salary ?? ''),
                (string)($student->annual_hours ?? ''),
                (string)($student->hourly_cost_worker_gross ?? ''),
                (string)($student->direction ?? ''),
                (string)($student->post_code ?? ''),             // “Código postal”
                (string)($student->province ?? ''),
                (string)($student->population ?? ''),
                (string)($student->iban ?? ''),
                (string)($student->observation ?? ''),           // en tu modelo era observation
                (string)($status),
            ];
        });

        $fileName = 'alumnos_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new StudentsExport($rows), $fileName);
    }

    /**
     * Import
     */
    public function import(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $students = json_decode($request->input('students'), true);
        $students['main_company_id'] = $mainCompanyId;

        Student::import($students);

        return $students;
    }
}

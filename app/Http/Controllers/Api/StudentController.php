<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Requests\StudentRequests;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentController extends BaseController
{


    /**
     * Obtener alumnos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $students = Student::student($mainCompanyId);
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $students = $students->leftjoin('registrations', 'registrations.student_id', '=', 'students.id')
                    ->leftjoin('courses', 'courses.id', '=', 'registrations.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->inactive == 'false') {
                $students = $students->where('students.active', 1);
            }
            if ($request->name) {
                $students = $students->where('students.name', 'like', '%'.$request->name.'%');
            }
            if ($request->surname) {
                $students = $students->where('students.surname', 'like', '%'.$request->surname.'&');
            }
            if ($request->dni) {
                $students = $students->where('students.dni', 'like', '%'.$request->dni.'%');
            }
            if ($request->telephone) {
                $students = $students->where('students.telephone', 'like', '%'.$request->dni.'%');
            }
            if ($request->email) {
                $students = $students->where('students.email', 'like', '%'.$request->email.'%');
            }
            if ($request->company) {
                $students = $students->where('companies.name', 'like', '%'.$request->company.'%');
            }

            $students = $students->orderBy('students.name','asc')
                ->groupBy('students.id', 'students.name')
                ->get();
            if (count($students) > 0) {
                foreach($students as $student) {
                    $registered = Registration::where('student_id', $student->id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();
                    if ($registered) {
                        $student['used'] = true;
                    } else {
                        $student['used'] = false;
                    }
                }
            }
            return $students;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener alumno
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $student = Student::student($mainCompanyId)
            ->where('students.id', $id)
            ->first();

        if ($student) {
            $registered = Registration::where('student_id', $student->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if ($registered) {
                $student['used'] = true;
            } else {
                $student['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'student' => $student
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    /**
     * Creamos alumno
     * @param StudentRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StudentRequests $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $element = Student::createWithService($data);

            $student = Student::student($mainCompanyId)
                ->where('students.id', $element->id)
                ->first();

            $registered = Registration::where('student_id', $student->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if ($registered) {
                $student['used'] = true;
            } else {
                $student['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'student' => $student
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editar alumno
     * @param $id
     * @param StudentRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, StudentRequests $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $student = Student::find($id);
            $element = $student->updateWithService($data);

            $student = Student::student($mainCompanyId)
                ->where('students.id', $element->id)
                ->first();
            $registered = Registration::where('student_id', $student->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if ($registered) {
                $student['used'] = true;
            } else {
                $student['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'student' => $student
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Comprobamos si existe alumno con ese DNI
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDni(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $student = Student::where('dni', $request['dni'])
        ->FilterMainCompany($mainCompanyId);

        if ($request['id']){
            $student = $student->where('id', '!=', $request['id']);
        }
        $student = $student->first();
        if ($student){
            return response()->json([
                'exists' => true
            ]);
        } else {
            return response()->json([
                'exists' => false
            ]);
        }
    }

    /**
     * Obtener los cursos del alumno
     * @param $id
     * @return mixed
     */
    public function getStudentsCourses($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $registrations = Registration::studentCourses($id, $mainCompanyId);

        $user = User::find(Auth::id());
        if ($user->teacher_id) {
            $registrations = $registrations->where('courses.teacher_id', $user->teacher_id);
        }
        $registrations = $registrations->get();

        if (count($registrations) > 0) {
            foreach ($registrations as $registration){
                $beginning = Carbon::parse($registration['beginning'])->format('d/m/Y');
                $registration['beginning'] = $beginning;
                $end = Carbon::parse($registration['end'])->format('d/m/Y');
                $registration['end'] = $end;
            }
        }
        return $registrations;
    }

    /**
     * Eliminar Alumno
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request) {
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
     * Obtener CSV de alumnos
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function studentsCSV(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $students = Student::student($mainCompanyId);

            if ($request->inactive == 'false') {
                $students = $students->where('students.active', 1);
            }
            if ($request->name) {
                $students = $students->where('students.name', 'like', '%'.$request->name.'%');
            }
            if ($request->surname) {
                $students = $students->where('students.surname', 'like', '%'.$request->surname.'&');
            }
            if ($request->dni) {
                $students = $students->where('students.dni', 'like', '%'.$request->dni.'%');
            }
            if ($request->telephone) {
                $students = $students->where('students.telephone', 'like', '%'.$request->dni.'%');
            }
            if ($request->email) {
                $students = $students->where('students.email', 'like', '%'.$request->email.'%');
            }
            if ($request->company) {
                $students = $students->where('companies.name', 'like', '%'.$request->company.'%');
            }

            $students = $students->orderBy('students.name','asc')->get();

            $data = [];
            if (count($students) > 0) {
                foreach ($students as $student) {
                    $disabled = $student['disabled'] === 1 ? 'Si' : 'No';
                    $status = $student['active'] === 1 ? 'Activo' : 'Inactivo';
                    $element = [
                        'Nombre' => $student['name'],
                        'Apellidos' => $student['surname'],
                        'DNI' => $student['dni'],
                        'Correo' => $student['email'],
                        'Teléfono' => $student['telephone'],
                        'Empresa' => $student['company'],
                        'Usuario' => $student['user'],
                        'Contraseña' => $student['password'],
                        'Fecha Nacimiento' => $student['level_study'],
                        'Descapacitado' => $disabled,
                        'Nº Seguridad Social' => $student['social_security_number'],
                        'C. Cotización' => $student['c_quote'],
                        'Grupo Cotización' => $student['quote_group'],
                        'Categoría Profesional' => $student['professional_category'],
                        'Salario Bruto Anual' => $student['annual_gross_salary'],
                        'Horas Anuales' => $student['annual_hours'],
                        'Coste Hora Bruto del Trabajador' => $student['hourly_cost_worker_gross'],
                        'Dirección' => $student['direction'],
                        'Código Postal' => $student['post_code'],
                        'Provincia' => $student['province'],
                        'Población' => $student['population'],
                        'Iban' => $student['iban'],
                        'Observaciones' => $student['observation'],
                        'Estado' => $status
                    ];
                    $data[] = $element;
                }
            } else {
                $element = [
                    'Nombre' => '',
                    'Apellidos' => '',
                    'DNI' => '',
                    'Correo' => '',
                    'Teléfono' => '',
                    'Empresa' => '',
                    'Usuario' => '',
                    'Contraseña' => '',
                    'Fecha Nacimiento' => '',
                    'Descapacitado' => '',
                    'Nº Seguridad Social' => '',
                    'C. Cotización' => '',
                    'Grupo Cotización' => '',
                    'Categoría Profesional' => '',
                    'Salario Bruto Anual' => '',
                    'Horas Anuales' => '',
                    'Coste Hora Bruto del Trabajador' => '',
                    'Dirección' => '',
                    'Código Postal' => '',
                    'Provincia' => '',
                    'Población' => '',
                    'Iban' => '',
                    'Observaciones' => '',
                    'Estado' => ''
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

    /**
     * Obtener alumnos activos
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function getActiveStudents(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $students = Student::student($mainCompanyId)
                ->where('students.active', 1)
                ->orderBy('students.name','asc')
                ->get();

            if (count($students) > 0) {
                foreach($students as $student) {
                    $registered = Registration::where('student_id', $student->id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();

                    if ($registered) {
                        $student['used'] = true;
                    } else {
                        $student['used'] = false;
                    }
                }
            }
            return $students;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function import(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $students = json_decode($request->input('students'), true);

        $students['main_company_id'] = $mainCompanyId;

        Student::import($students);

        return $students;
    }
}

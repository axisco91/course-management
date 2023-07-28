<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\TeacherRequests;
use App\Models\Course;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TeacherController extends BaseController
{
    private $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Obtener docentes
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeachers() {
        try {
            $teachers = Teacher::teacher()
                ->orderBy('teachers.name','asc')
                ->get();
            if (count($teachers) > 0){
                foreach ($teachers as $teacher) {
                    $course = Course::where('teacher_id', $teacher->id)->first();
                    if ($course) {
                        $teacher['used'] = true;
                    } else {
                        $teacher['used'] = false;
                    }
                    $teacher['teacher_areas'] = $teacher->teacherAreas()->select('id as value', 'name as label')->get()->toArray();
                }
            }
            return $teachers;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    // Obtain student
    public function getTeacher($id){
        $teacher = Teacher::teacher()
            ->where('teachers.id', $id)
            ->first();
        if ($teacher) {
            $course = Course::where('teacher_id', $teacher->id)
                ->first();
            if ($course) {
                $teacher['used'] = true;
            } else {
                $teacher['used'] = false;
            }
            $teacher['teacher_areas'] = $teacher->teacherAreas()->select('id as value', 'name as label')->get()->toArray();
            return response()->json([
                'status' => 200,
                'teacher' => $teacher
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Docente no existe'
        ]);
    }

    /**
     * Creamos docente
     * @param TeacherRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(TeacherRequests $request){
        try {
            $data = $request->all();
            $element = $this->teacherService->create($data);
            $teacher = Teacher::teacher()
                ->where('teachers.id', $element->id)
                ->first();
            $course = Course::where('teacher_id', $teacher->id)
                ->first();
            if ($course) {
                $teacher['used'] = true;
            } else {
                $teacher['used'] = false;
            }
            $teacher['teacher_areas'] = $teacher->teacherAreas()->select('id as value', 'name as label')->get()->toArray();
            return response()->json([
                'status' => 200,
                'teacher' => $teacher
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Editamos docente
     * @param $id
     * @param TeacherRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, TeacherRequests $request){
        try {
            $data = $request->all();
            $teacher = Teacher::find($id);
            $element = $this->teacherService->update($teacher, $data);
            $teacher = Teacher::teacher()
                ->where('teachers.id', $element->id)
                ->first();
            $course = Course::where('teacher_id', $teacher->id)
                ->first();
            if ($course) {
                $teacher['used'] = true;
            } else {
                $teacher['used'] = false;
            }
            $teacher['teacher_areas'] = $teacher->teacherAreas()->select('id as value', 'name as label')->get()->toArray();
            return response()->json([
                'status' => 200,
                'teacher' => $teacher
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Comprobamos DNI
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDni(Request $request){
        $teacher = Teacher::where('dni', $request->dni);
        if ($request->id){
            $teacher = $teacher->where('id', '!=', $request->id);
        }
        $teacher = $teacher->first();
        if ($teacher){
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
     * Obtener cursos de docente
     * @param $id
     * @return mixed
     */
    public function getTeachersCourses($id) {
        $courses = Course::teacherCourses($id)
            ->get();
        if (count($courses) > 0) {
            foreach ($courses as $course){
                $beginning = Carbon::parse($course['beginning'])->format('d/m/Y');
                $course['beginning'] = $beginning;
                $end = Carbon::parse($course['end'])->format('d/m/Y');
                $course['end'] = $end;
            }
        }
        return $courses;
    }

    /**
     * Eliminar docente
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                Teacher::destroy($id);
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
     * Obtenemos el CSV de los docentes
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function teachersCSV(Request $request){
        try {
            $teachers = Teacher::teacher();

            if ($request->name) {
                $teachers = $teachers->where('teachers.name', 'like', '%'.$request->name.'%');
            }
            if ($request->surname) {
                $teachers = $teachers->where('teachers.surname', 'like', '%'.$request->surname.'&');
            }
            if ($request->dni) {
                $teachers = $teachers->where('teachers.dni', 'like', '%'.$request->dni.'%');
            }
            if ($request->telephone) {
                $teachers = $teachers->where('teachers.telephone', 'like', '%'.$request->telephone.'%');
            }
            if ($request->email) {
                $teachers = $teachers->where('teachers.email', 'like', '%'.$request->email.'%');
            }
            if ($request->inactive == 'false') {
                $teachers = $teachers->where('teachers.active', 1);
            }

            $teachers = $teachers
                ->orderBy('teachers.name','asc')
                ->get();

            $data = [];
            if (count($teachers) > 0) {
                foreach ($teachers as $teacher) {
                    $status = $teacher['active'] === 1 ? 'Activo' : 'Inactivo';
                    $element = [
                        'Nombre' => $teacher['name'],
                        'Apellidos' => $teacher['surname'],
                        'DNI' => $teacher['dni'],
                        'Correo' => $teacher['email'],
                        'Teléfono' => $teacher['telephone'],
                        'Usuario' => $teacher['user'],
                        'Password' => $teacher['password'],
                        'Observaciones' => $teacher['observations'],
                        'Iban' => $teacher['iban'],
                        'Dirección' => $teacher['address'],
                        'Código Postal' => $teacher['post_code'],
                        'Provincia' => $teacher['province'],
                        'Población' => $teacher['population'],
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
                    'Usuario' => '',
                    'Password' => '',
                    'Observaciones' => '',
                    'Iban' => '',
                    'Dirección' => '',
                    'Código Postal' => '',
                    'Provincia' => '',
                    'Población' => '',
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
}

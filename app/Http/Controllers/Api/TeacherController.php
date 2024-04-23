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
    public function index(Request $request) {
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
            $teachers = $teachers->orderBy('teachers.name','asc')
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

    /**
     * Obtener docentes activos
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeTeachers() {
        try {
            $teachers = Teacher::teacher()
                ->where('active', 1)
                ->orderBy('teachers.name','asc')
                ->get();
            return $teachers;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    // Obtain student
    public function show($id){
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

    
    public function store(TeacherRequests $request){
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

            // Asociar las áreas formativas al profesor
            $teacherAreaIds = $request->input('teacher_area_ids');  // Los IDs de las áreas a las que está relacionado el profesor
            foreach ($teacherAreaIds as $teacherAreaId) {
                $teacher->teacherAreas()->attach($teacherAreaId);
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

    public function update($id, TeacherRequests $request){
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

            // Actualizar las áreas formativas asociadas al profesor
            $teacher->teacherAreas()->detach();
            $teacherAreaIds = $request->input('teacher_area_ids');  // Los IDs de las áreas a las que está relacionado el profesor
            foreach ($teacherAreaIds as $teacherAreaId) {
                $teacher->teacherAreas()->attach($teacherAreaId);
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
}

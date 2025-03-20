<?php

namespace App\Http\Controllers\Api;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CourseController extends BaseController
{
    public function index(Request $request) {
        try {
            $query = Course::withCourseData();

            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $query->where('teacher_id', $user->teacher_id);
            } else if ($user->advisor_id) {
                $query->leftjoin('billings', 'billings.course_id', '=', 'courses.id')
                    ->where('advisor_id', $user->advisor_id);
            }

            if ($request->formative_action) {
                $query = $query->where('courses.name', 'like', '%'.$request->formative_action.'%');
            }
            if ($request->name) {
                $query = $query->where('courses.name', 'like', '%'.$request->name.'&');
            }
            if ($request->group) {
                $query = $query->where('courses.group', 'like', '%'.$request->group.'%');
            }
            if ($request->type) {
                $query = $query->where('course_types.name', $request->type);
            }
            if ($request->status) {
                $query = $query->where('course_statuses.name', $request->status);
            }
            if ($request->company) {
                $registrations = Registration::where('company_id', $request->company)->groupBy('course_id')->pluck('course_id')->toArray();
                $query = $query->where(function ($query) use ($registrations){
                    $query->WhereIn('courses.id', $registrations);
                });
            }

            $courses = $query->get();

            foreach($courses as $course) {
                $registration = Registration::where('course_id', $course->id)->first();
                if ($registration) {
                    $course['used'] = true;
                } else {
                    $course['used'] = false;
                }
                $course['number_registrations'] = $course->registrations->count();
            }

            return $courses;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request){
        try {
            $course = Course::createCourse($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course' => Course::withCourseData($course->id)->Where('courses.id', $course->id)->first()
        ]);
    }

    public function update($id, Request $request){
        try {
            \Log::info('Updating course: ' . $id);
            \Log::info('Received data: ' . json_encode($request->all()));

            $course = Course::updateCourse($id, $request->all());

            \Log::info('Updated course: ' . json_encode($course));
        } catch (\Exception $e){
            \Log::error('Error updating course: ' . $e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course' => Course::withCourseData($course->id)->Where('courses.id', $course->id)->first()
        ]);
    }
    public function show($id){
        $course = Course::withCourseData()
            ->where('courses.id', $id)->first();

        if ($course) {
            return response()->json([
                'status' => 200,
                'course' => $course
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Curso no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Course::destroy($id);
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

    public function setData(Request $request) {
        $course = Course::setName($request->training_action_id, $request->id);
        return response()->json($course);
    }

    public function getStudents($id){
        return response()->json(Student::getRegistrated($id));
    }

    public function count(){
        return Course::count();
    }

    public function coursesCSV(Request $request){
        try {
            if ($request) {
                return Course::getCourseCSV($request['formative_action'], $request['name'], $request['group'], $request['type'], $request['status'], $request['company']);
            }
            return Course::getCourseCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
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
    public function resetTracingsIfCancelled($id)
    {
        Log::info('Resetting tracings for course: ' . $id);
        $course = Course::find($id);
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
                return response()->json([
                    'status' => 200,
                    'message' => 'Seguimientos futuros restablecidos con éxito'
                ]);
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
}

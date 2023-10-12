<?php

namespace App\Http\Controllers\Api;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseController extends BaseController
{
    public function index(Request $request) {
        try {
            $courses = Course::withCourseData();

            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $courses->where('teacher_id', $user->teacher_id);
            }

            if ($request->formative_action) {
                $courses = $courses->where('courses.name', 'like', '%'.$request->formative_action.'%');
            }
            if ($request->name) {
                $courses = $courses->where('courses.name', 'like', '%'.$request->name.'&');
            }
            if ($request->group) {
                $courses = $courses->where('courses.group', 'like', '%'.$request->group.'%');
            }
            if ($request->type) {
                $courses = $courses->where('course_types.name', $request->type);
            }
            if ($request->status) {
                $courses = $courses->where('course_statuses.name', $request->status);
            }
            if ($request->company) {
                $registrations = Registration::where('company_id', $request->company)->groupBy('course_id')->pluck('course_id')->toArray();
                $courses = $courses->where(function ($query) use ($registrations){
                    $query->WhereIn('courses.id', $registrations);
                });
            }

            foreach($courses as $course) {
                $registration = Registration::where('course_id', $course->id)->first();
                if ($registration) {
                    $course['used'] = true;
                } else {
                    $course['used'] = false;
                }
                $course['number_registrations'] = $course->registrations->count();
            }

            return $courses->orderBy('courses.beginning', 'desc')->get();
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
            $course = Course::updateCourse($id, $request);
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
}

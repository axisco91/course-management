<?php

namespace App\Http\Controllers\API;
use App\Models\Course;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseController extends BaseController
{
    public function getCourses() {
        try {
            return Course::getCourses();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
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
            'course' => Course::getCourse($course->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $course = Course::updateCourse($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'center' => Course::getCourse($course->id)
        ]);
    }

    public function getCourse($id){
        $course = Course::getCourse($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function setData(Request $request) {
        $course = Course::setName($request->training_action_id, $request->id);
        return response()->json($course);
    }

    public function getStudents($id){
        return response()->json(Registration::getRegistrated($id));
    }

    public function count(){
        return Course::count();
    }
}

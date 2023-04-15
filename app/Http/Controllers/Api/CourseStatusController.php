<?php

namespace App\Http\Controllers\API;
use App\Models\CourseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseStatusController extends BaseController
{
    public function getCourseStatuses() {
        try {
            return CourseStatus::getCourseStatuses();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $course = CourseStatus::createCourseStatus($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course_status' => $course
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $course = CourseStatus::updateCourseStatus($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course_status' => $course
        ]);
    }

    public function getCourseStatus($id){
        $status = CourseStatus::find($id);
        if ($status) {
            return response()->json([
                'status' => 200,
                'course_status' => $status
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Estado no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CourseStatus::destroy($id);
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

    public function count(){
        return CourseStatus::count();
    }
}

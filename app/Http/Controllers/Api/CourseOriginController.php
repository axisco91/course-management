<?php

namespace App\Http\Controllers\Api;
use App\Models\CourseOrigin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseOriginController extends BaseController
{
    public function getCourseOrigins() {
        try {
            return CourseOrigin::getCourseOrigins();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $origin = CourseOrigin::createCourseOrigin($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course_type' => CourseOrigin::getCourseOrigin($origin->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $origin = CourseOrigin::updateCourseOrigin($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course_type' => CourseOrigin::getCourseOrigin($origin->id)
        ]);
    }

    public function getCourseOrigin($id){
        $type = CourseOrigin::getCourseType($id);
        if ($type) {
            return response()->json([
                'status' => 200,
                'course_type' => $type
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CourseOrigin::destroy($id);
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

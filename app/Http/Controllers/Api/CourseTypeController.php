<?php

namespace App\Http\Controllers\API;
use App\Models\CourseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseTypeController extends BaseController
{
    public function getCourseTypes() {
        try {
            return CourseType::getCourseTypes();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $type = CourseType::createCourseType($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course_type' => CourseType::getCourseType($type->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $type = CourseType::updateCourseType($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'course_type' => CourseType::getCourseType($type->id)
        ]);
    }

    public function getCourseType($id){
        $type = CourseType::getCourseType($id);
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
                CourseType::destroy($id);
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
        return CourseType::count();
    }
}

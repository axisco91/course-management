<?php

namespace App\Http\Controllers\API;
use App\Models\Course;
use App\Models\Student;
use App\Models\Tracing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TracingController extends BaseController
{
    public function getTracings() {
        try {
            return Tracing::getTracings();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $tracing = Tracing::createTracing($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'tracing' => Tracing::getTracing($tracing->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $tracing = Tracing::updateTracing($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'tracing' => Tracing::getTracing($tracing->id)
        ]);
    }

    public function getTracing($id){
        $tracing = Tracing::getTracing($id);
        if ($tracing) {
            $course = Course::where('id', $tracing->course_id)->first();
            $student = Student::where('id', $tracing->student_id)->first();
            $tracing['name'] = $course->group.'/'. $course->name .' - '. $student->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return response()->json([
                'status' => 200,
                'tracing' => $tracing
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Seguimiento no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Tracing::destroy($id);
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

    public function count(){
        return Tracing::count();
    }

    public function tracingsCSV(Request $request){
        try {
            if ($request) {
                return Tracing::getTracingCSV($request['course'], $request['company'], $request['student'], $request['status'], $request['beginning'], $request['end']);
            }
            return Tracing::getTracingCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

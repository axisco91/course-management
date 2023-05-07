<?php

namespace App\Http\Controllers\API;
use App\Models\Course;
use App\Models\Profitability;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfitabilityController extends BaseController
{
    public function getProfitabilities() {
        try {
            return Profitability::getProfitabilities();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $profitability = Profitability::createProfitability($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'profitability' => $profitability
        ]);
    }

    public function edit($id, Request $request){
        try {
            $profitability = Profitability::updateProfitability($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'profitability' => $profitability
        ]);
    }

    public function getProfitability($id){
        $profitability = Profitability::find($id);
        if ($profitability) {
            $course = Course::where('id', $profitability->course_id)->first();
            $student = Student::where('id', $profitability->student_id)->first();
            $profitability['name'] = $course->group.'/'. $course->name .' - '. $student->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return response()->json([
                'status' => 200,
                'profitability' => $profitability
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Rentabilidad no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Profitability::destroy($id);
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

    public function getStudents($id){
        $profitabilities = Profitability::find($id);
        $registrations = $profitabilities->registrations()->get()->pluck('student_id')->toArray();
        $students = Student::whereIn('id', $registrations)->get();
        return response()->json($students);
    }

    public function count(){
        return Profitability::count();
    }
}

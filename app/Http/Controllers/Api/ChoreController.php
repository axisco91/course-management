<?php

namespace App\Http\Controllers\API;
use App\Models\Chore;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChoreController extends BaseController
{
    public function getChores() {
        try {
            return Chore::getChores();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $chore = Chore::createChore($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'chore' => $chore
        ]);
    }

    public function edit($id, Request $request){
        try {
            $chore = Chore::updateChore($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'chore' => $chore
        ]);
    }

    public function getChore($id){
        $chore = Chore::find($id);
        if ($chore) {
            $course = Course::where('id', $chore->course_id)->first();
            $student = Student::where('id', $chore->student_id)->first();
            $chore['name'] = $course->group.'/'. $course->name .' - '. $student->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return response()->json([
                'status' => 200,
                'chore' => $chore
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
                Chore::destroy($id);
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
        return Chore::count();
    }

    public function choresCSV(Request $request){
        try {
            if ($request) {
                return Chore::getChoreCSV($request['course'], $request['company'], $request['student'], $request['status'], $request['beginning'], $request['end']);
            }
            return Chore::getChoreCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

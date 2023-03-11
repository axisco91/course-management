<?php

namespace App\Http\Controllers\API;
use App\Models\TeacherArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherAreaController extends BaseController
{
    public function teacherAreas() {
        try {
            return TeacherArea::getTeacherArea();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = [
            'name' => $request->name
        ];

        return TeacherAreaController::createTeacherAreas();;
    }

    public function edit($id, Request $request){
        $data = [
            'name' =>$request->name
        ];
        $training_action = TrainingActionLevel::updateTrainingActionLevel($id, $data);
        if ($training_action){
            return 1;
        } else {
            return 0;
        }
    }

    public function getTrainingActionLevel($id){
        return TrainingActionLevel::find($id);
    }

    public function destroy($id){
        if ($id) {
            TrainingActionLevel::destroy($id);
            return 1;
        }
    }
}

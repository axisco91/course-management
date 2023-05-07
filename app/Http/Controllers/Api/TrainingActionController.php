<?php

namespace App\Http\Controllers\API;
use App\Models\Course;
use App\Models\TrainingAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingActionController extends BaseController
{
    public function getTrainingActions() {
        try {
            return TrainingAction::getTrainingActions();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function getActiveTrainingActions() {
        try {
            return TrainingAction::select('id as value', 'name as label')->where('active', 1)->get();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        try {
            $training_action = TrainingAction::createTrainingAction($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 200,
            'training_action' => TrainingAction::getTrainingAction($training_action->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $training_action = TrainingAction::updateTrainingAction($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_action' => TrainingAction::getTrainingAction($training_action->id)
        ]);
    }

    public function getTrainingAction($id){
        $training_action = TrainingAction::getTrainingAction($id);
        if ($training_action) {
            return response()->json([
                'status' => 200,
                'training_action' => $training_action
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Acción Formativa no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingAction::destroy($id);
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

    public function getFormativeAction(){
        $training = TrainingAction::orderBy('id', 'desc')->first();
        $id = $training['id']+1;
        if ($id < 10) {
            $formative_action = '00'.$id;
        }
        else if ($id < 100) {
            $formative_action = '0'.$id;
        } else {
            $formative_action = $id;
        }
        return response()->json([
            'formative_action' => $formative_action
        ]);
    }

    public function getCourses($id) {
        return Course::getTrainingActionCourse($id);
    }

    public function count(){
        return TrainingAction::count();
    }
}

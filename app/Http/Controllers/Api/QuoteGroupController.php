<?php

namespace App\Http\Controllers\API;
use App\Models\TrainingActionLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class QuoteGroupController extends BaseController
{
    public function getTrainingActionLevels() {
        return TrainingActionLevel::all();
    }

    public function create(Request $request){
        $data = [
            'name' => $request->name
        ];

        return TrainingActionLevel::createTrainingActionLevel();;
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

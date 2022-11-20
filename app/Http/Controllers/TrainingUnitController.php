<?php

namespace App\Http\Controllers;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingUnitController extends Controller
{
    public function index() {

        return view('training-units.index');
    }

    public function edit($id){
        return view('training-units.update', compact('id'));
    }

    public function create(){
        return view('training-units.create');
    }

    public function view($id){
        return view('training-units.view', compact('id'));
    }

    public function getTrainingUnitsTable(Request $request){
        $training_units = TrainingUnit::all();
        foreach ($training_units as $training_unit){
            $training_unit['status'] = '';
            $training_unit['actions'] = '';
        }
        return response()->json($training_units);
    }
}

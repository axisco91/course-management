<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingActionController extends Controller
{
    public function index() {

        return view('training-actions.index');
    }

    public function edit($id){
        return view('training-actions.update', compact('id'));
    }

    public function create(){
        return view('training-actions.create');
    }

    public function view($id){
        return view('training-actions.view', compact('id'));
    }
}

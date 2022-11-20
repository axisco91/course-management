<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractController extends Controller
{
    public function index() {

        return view('training-contracts.index');
    }

    public function edit($id){
        return view('training-contracts.update', compact('id'));
    }

    public function create(){
        return view('training-contracts.create');
    }

    public function view($id){
        return view('training-contracts.view', compact('id'));
    }

    public function secondPhase($id){
        return view('training-contracts.second-phase', compact('id'));
    }
}

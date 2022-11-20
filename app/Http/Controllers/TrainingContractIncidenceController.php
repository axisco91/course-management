<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractIncidenceController extends Controller
{
    public function index($training_contract_id){
        return view('training-contract-incidences.index', compact('training_contract_id'));
    }

    public function edit($id){
        return view('training-contract-incidences.update', compact('id'));
    }

    public function create($training_contract_id){
        return view('training-contract-incidences.create', compact('training_contract_id'));
    }
}

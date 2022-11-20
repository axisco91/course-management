<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdvisorIncidenceController extends Controller
{
    public function edit($id){
        return view('advisor-incidences.update', compact('id'));
    }

    public function create($advisor_id){
        return view('advisor-incidences.create', compact('advisor_id'));
    }
}

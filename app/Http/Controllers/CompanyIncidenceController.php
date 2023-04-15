<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyIncidenceController extends Controller
{
    public function edit($id){
        return view('company-incidences.update', compact('id'));
    }

    public function create($company_id){
        return view('company-incidences.create', compact('company_id'));
    }
}

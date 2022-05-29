<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index() {

        return view('companies.index');
    }

    public function edit($id){
        return view('companies.update', compact('id'));
    }

    public function create(){
        return view('companies.create');
    }
}

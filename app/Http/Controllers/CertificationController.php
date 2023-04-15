<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CertificationController extends Controller
{

    public function index() {

        return view('certifications.index');
    }

    public function edit($id){
        return view('certifications.update', compact('id'));
    }

    public function create(){
        return view('certifications.create');
    }

    public function view($id){
        return view('certifications.view', compact('id'));
    }
}

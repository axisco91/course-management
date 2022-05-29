<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index() {

        return view('students.index');
    }

    public function edit($id){
        return view('students.update', compact('id'));
    }

    public function create(){
        return view('students.create');
    }
}

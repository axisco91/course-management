<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index() {

        return view('teachers.index');
    }

    public function edit($id){
        return view('teachers.update', compact('id'));
    }
}

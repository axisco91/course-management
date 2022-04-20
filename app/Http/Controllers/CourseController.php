<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index() {

        return view('courses.index');
    }

    public function update($id){
        return view('courses.update', compact('id'));
    }
}

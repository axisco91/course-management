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

    public function edit($id){
        return view('courses.update', compact('id'));
    }

    public function create($id = null){
        return view('courses.create', compact('id'));
    }

    public function view($id){
        return view('courses.view', compact('id'));
    }
}

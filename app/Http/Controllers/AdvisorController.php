<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdvisorController extends Controller
{
    public function index() {

        return view('advisors.index');
    }

    public function edit($id){
        return view('advisors.update', compact('id'));
    }

    public function create(){
        return view('advisors.create');
    }

    public function view($id){
        return view('advisors.view', compact('id'));
    }
}

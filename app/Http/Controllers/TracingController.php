<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TracingController extends Controller
{
    public function index() {

        return view('tracings.index');
    }

    public function edit($id){
        return view('tracings.update', compact('id'));
    }
}

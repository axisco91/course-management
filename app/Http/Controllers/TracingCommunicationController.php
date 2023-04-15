<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TracingCommunicationController extends Controller
{
    public function edit($id){
        return view('tracing-communications.update', compact('id'));
    }

    public function create($tracing_id){
        return view('tracing-communications.create', compact('tracing_id'));
    }
}

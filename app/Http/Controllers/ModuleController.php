<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{

    public function index() {

        return view('modules.index');
    }

    public function edit($id){
        return view('modules.update', compact('id'));
    }

    public function create(){
        return view('modules.create');
    }

    public function view($id){
        return view('modules.view', compact('id'));
    }
}

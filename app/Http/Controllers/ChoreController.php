<?php

namespace App\Http\Controllers;

class ChoreController extends Controller
{
    public function index() {

        return view('chores.index');
    }

    public function edit($id){
        return view('chores.update', compact('id'));
    }

    public function view($id){
        return view('chores.view', compact('id'));
    }
}

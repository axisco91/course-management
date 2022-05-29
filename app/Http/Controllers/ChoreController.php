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
}

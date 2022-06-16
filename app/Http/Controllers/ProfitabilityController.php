<?php

namespace App\Http\Controllers;

class ProfitabilityController extends Controller
{
    public function index() {

        return view('profitabilities.index');
    }

    public function edit($id){
        return view('profitabilities.update', compact('id'));
    }

    public function view($id){
        returnview('profitabilities.view', compact('id'));
    }
}

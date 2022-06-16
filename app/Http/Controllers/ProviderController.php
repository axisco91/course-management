<?php

namespace App\Http\Controllers;

class ProviderController extends Controller
{
    public function index() {

        return view('providers.index');
    }

    public function edit($id){
        return view('providers.update', compact('id'));
    }

    public function create(){
        return view('providers.create');
    }

    public function view($id){
        return view('providers.view', compact('id'));
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    public function index() {

        return view('billings.index');
    }

    public function edit($id){
        return view('billings.update', compact('id'));
    }
}

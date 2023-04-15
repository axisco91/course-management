<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PotentialCompanyController extends Controller
{
    public function index() {

        return view('potential-companies.index');
    }

    public function create(){
        return view('potential-companies.create');
    }

    public function convert(){
        return view('potential-companies.convert');
    }

    public function view($id){
        return view('potential-companies.view', compact('id'));
    }

    public function finalized(){
        return view('potential-companies.finalized');
    }
}

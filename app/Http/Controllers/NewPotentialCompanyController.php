<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class NewPotentialCompanyController extends Controller
{
    public function index() {

        return view('new-potential-companies.index');
    }

    public function create(){
        return view('new-potential-companies.create');
    }

    public function convert(){
        return view('new-potential-companies.convert');
    }

    public function view($id){
        return view('new-potential-companies.view', compact('id'));
    }
}

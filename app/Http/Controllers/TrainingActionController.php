<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingActionController extends Controller
{
    public function index() {

        return view('training-actions.index');
    }

}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PotentialStudentController extends Controller
{
    public function index() {
        return view('potential-students.index');
    }

    public function create(){
        return view('potential-students.create');
    }

    public function createPrivate(){
        return view('potential-students.create-private');
    }

    public function convert($id){
        return view('potential-students.convert', compact('id'));
    }

    public function view($id){
        return view('potential-students.view', compact('id'));
    }

    public function finalized(){
        return view('potential-students.finalized');
    }

    public function finalizedPrivateStudent(){
        return view('potential-students.finalized_private_student');
    }
}

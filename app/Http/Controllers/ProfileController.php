<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index() {

        $id = Auth::id();
        if ($id){
            return view('profile.index', compact('id'));
        }
    }

    public function edit() {

        $id = Auth::id();
        if ($id){
            return view('profile.setting-profile', compact('id'));
        }
    }

}

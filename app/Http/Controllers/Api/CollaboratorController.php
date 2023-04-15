<?php

namespace App\Http\Controllers\API;
use App\Models\TrainingActionLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CollaboratorController extends BaseController
{
    public function collaborators() {
        return User::select('*', 'id as value', DB::raw("CONCAT(users.name,' ',users.surname) as label"))->where('has_commission', 1)->get();
    }
}

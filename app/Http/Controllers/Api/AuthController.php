<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends BaseController
{

    public function signin(Request $request)
    {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
            $authUser = Auth::user();
            $success['accessToken'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['fullname'] =  $authUser->name.' '.$authUser->surname;
            $success['username'] = $authUser->username;
            $success['email'] = $authUser->email;
            $success['ability'] = [['action' => "manage", 'subject' => "all"]];
            $success['role'] = 'admin';
            return $this->sendResponse($success, 'User signed in');
        }
        else{
            // return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
            $success['success'] = false;
            $this->sendResponse($success, 'Unauthorised');
        }
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;
        $success['name'] =  $user->surname;

        return $this->sendResponse($success, 'User created successfully.');
    }

}

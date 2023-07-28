<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Role;
use Validator;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;

class AuthController extends BaseController
{

    public function signin(Request $request)
    {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password, 'active' => 1])){
            $authUser = Auth::user();
            $roles = Auth::user()->getRoleNames();
            $permissions = $authUser->getAllPermissions()->pluck('name');
            $success['ability'] = [];
            if ($permissions) {
                foreach ($permissions as $permission) {
                    $ability = explode('.', $permission);
                    $success['ability'][] = ['action' => $ability[0], 'subject' => $ability[1]];
                }
            } else {
                $success['ability'][] = ['action' => 'manage', 'subject' => 'all'];
            }
            $success['ability'][] = ['action' => 'manage', 'subject' => 'all'];
            $success['accessToken'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['fullname'] =  $authUser->name.' '.$authUser->surname;
            $success['username'] = $authUser->username;
            $success['email'] = $authUser->email;
            $success['role'] = $roles && isset($roles[0]) ? $roles[0] : 'admin';
            $success['avatar'] = $authUser->profile_photo_path;
        //    $success['permissions'] = ['create_students', 'edit_students'];
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

    public function logout(Request $request) {
        if ($request->has('username') && $request->has('token')) {
            $user = User::where('username', $request->username)->first();
            if ($user) {
                $access = explode(" ", $request->token);
                $personal_access = PersonalAccessToken::where('tokenable_id', $user->id)
                    ->where('id', $access[0])
                    ->first();
                    if ($personal_access) {
                        $personal_access->delete();
                        return response()->json([
                            'status', 200
                        ]);
                    }
            }
        }
        return response()->json([
            'status', 400
        ]);
    }

}

<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Http\Request;

class AuthService
{
    /**
     * Función para crear un tipo de acción
     * @param array $data
     * @return mixed
     */
    public function loginData(User $user, Request $request)
    {
        $permissions = $user->getAllPermissions()->pluck('name');
        $token = $user->createToken('MyAuthApp');
        $plainTextToken = $token->plainTextToken;
        $success['id'] =  $user->id;
        $success['accessToken'] =  $plainTextToken;
        $success['fullname'] =  $user->name.' '.$user->surname1;
        $success['administrator'] = 1;
        $success['username'] = $user->username;
        $success['email'] = $user->email;
        $success['ability'] = [['action' => "manage", 'subject' => "all"]];
        $success['avatar'] = $user->profile_photo_path;
        $success['permissions'] = $permissions;
        //$success['user'] = BasicUserResource::make($user);

         return $success;
    }
}

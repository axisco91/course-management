<?php

namespace App\Http\Controllers\Api;
use App\Models\TrainingActionLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Obtenemos usuarios
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers() {
        try {
            $users = User::getUser()
                ->get();
            foreach ($users as $user) {
                $roles = $user->roles;
                foreach ($roles as $role) {
                    $user['role'] = $role->name;
                    $user['role_id'] = $role->id;
                }
            }
            return $users;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenemos usuario
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($id){
        $user = User::getUser()
            ->where('users.id', $id)
            ->first();
        if ($user) {
            $roles = $user->roles;
            foreach ($roles as $role) {
                $user['role'] = $role->name;
                $user['role_id'] = $role->id;
            }
            return response()->json([
                'status' => 200,
                'user' => $user
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Usuario no existe'
        ]);
    }

    public function create(Request $request){
        try {
            $user = User::createUser($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'user' => User::getUser()->where('users.id', $user->id)
                ->first()
        ]);
    }

    public function edit($id, Request $request){
        try {
            $this->uploadImage($request, $id);
            $user = User::updateUser($id, $request);
            $user = User::find($id);
            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = substr(str_shuffle(MD5(microtime())), 0, 10).substr(str_shuffle(MD5($user->name.'-'.$user->surname)), 0, 10).'.'.$file->getClientOriginalExtension();
                $destination_path = public_path() . '/images/avatar/';
                $request->file('image')->move($destination_path, $filename);
                $file = '/images/avatar/'.$filename;
                $user->update([
                    'profile_photo_path' => $file
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'user' => User::getUser()->where('users.id', $id)
                ->first()
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                User::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function uploadImage(Request $request, $id) {
        $user = User::find($id);
        return $request->file('image');
        if ($request->file('image')) {
            try {
                $file = $request->file('image');
                $filename = substr(str_shuffle(MD5(microtime())), 0, 10).substr(str_shuffle(MD5($user->name.'-'.$user->surname)), 0, 10).'.'.$file->getClientOriginalExtension();
                $destination_path = public_path() . '/images/avatar/';
                $request->file('image')->move($destination_path, $filename);
                $file = '/images/avatar/'.$filename;
                $user->update([
                    'profile_photo_path' => $file
                ]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            return response()->json([
                'status' => 200
            ]);
        }
        return 'Pringado';
    }
    public function changePassword($id, Request $request) {
        try {
            $user = User::find($id);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return response()->json([
            'status' => 200
        ]);
    }
}

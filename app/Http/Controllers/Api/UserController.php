<?php

namespace App\Http\Controllers\Api;
use App\Models\TrainingActionLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function getUsers() {
        try {
            return User::getUsers();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
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
            'user' => User::getUser($user->id)
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
            'user' => User::getUser($user->id)
        ]);
    }

    public function getUser($id){
        $user = User::getUser($id);
        if ($user) {
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

    public function count(){
        return User::count();
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
}

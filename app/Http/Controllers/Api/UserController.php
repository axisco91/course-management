<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    /**
     * Obtenemos usuarios
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $users = User::getUser($mainCompanyId)
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
    public function getUser($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $user = User::getUser($mainCompanyId)
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $user = User::where('username', $data['username'])->first();

            if (!$user) {
                $user = User::createWithService($data);

                return response()->json([
                    'status' => 200,
                    'user' => User::getUser($mainCompanyId)->where('users.id', $user->id)
                        ->first()
                ]);
            }

        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 300,
            'user' => User::getUser($mainCompanyId)->where('users.id', $user->id)
                ->first()
        ]);
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $user = User::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Usuario no existe'
                ]);
            }
            $data = $request->all();

            $user->updateWithService($data);
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

            return response()->json([
                'status' => 200,
                'user' => User::getUser($mainCompanyId)->where('users.id', $id)
                    ->first()
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $user = User::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$user) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Usuario no existe'
                    ]);
                }

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

    public function changePassword($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $user = User::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Usuario no existe'
                ]);
            }

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

    public function indexWithCommissions(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $users = User::with('commissions')
            ->where('main_company_id', $mainCompanyId)
            ->get();
        return response()->json($users);
    }
}

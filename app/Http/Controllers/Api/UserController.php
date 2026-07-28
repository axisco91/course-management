<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\UserResource;
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
            $query = User::getUser($mainCompanyId);

            if ($request->filled('name')) {
                $query = $query->where('users.name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('surname')) {
                $query = $query->where('users.surname', 'like', '%' . $request->surname . '%');
            }
            if ($request->filled('email')) {
                $query = $query->where('users.email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('active')) {
                $query = $query->where('users.active', (int) $request->active);
            }

            $sortParam = (string) $request->get('sort', '-id');
            $direction = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
            $sortField = ltrim($sortParam, '-');

            $sortMap = [
                'id' => 'users.id',
                'name' => 'users.name',
                'surname' => 'users.surname',
                'email' => 'users.email',
                'active' => 'users.active',
                'status' => 'users.active',
            ];

            if ($sortField === 'name') {
                $query = $query->orderBy('users.name', $direction)->orderBy('users.surname', $direction);
            } else {
                $sortColumn = $sortMap[$sortField] ?? 'users.id';
                $query = $query->orderBy($sortColumn, $direction);
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $users = UserResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'users' => $users,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $users = UserResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'users' => $users,
                ],
                trans('Obtenido con éxito')
            );
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
            return $this->sendResponse(
                [
                    'user' => $user,
                ],
                trans('Obtenido con éxito')
            );
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

                return $this->sendResponse(
                    [
                        'user' => User::getUser($mainCompanyId)->where('users.id', $user->id),
                    ],
                    trans('Obtenido con éxito')
                );
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

            return $this->sendResponse(
                [
                    'user' => User::getUser($mainCompanyId)->where('users.id', $id),
                ],
                trans('Obtenido con éxito')
            );
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
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
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
        return $this->sendResponse(
            [],
            trans('Cambiado con éxito')
        );
    }

    public function indexWithCommissions(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $users = User::with('commissions')
            ->where('main_company_id', $mainCompanyId)
            ->get();
        return response()->json($users);
    }

    public function basicUser(Request $request) {
        $authUser = Auth::user();
        $roles = Auth::user()->getRoleNames();
        $permissions = $authUser->getAllPermissions()->pluck('name');
        $success['roles'] = $roles;
        $success['permissions'] = $permissions;
        $success['ability'] = [];
        foreach ($permissions as $permission) {
            $ability = explode('.', $permission);
            $success['ability'][] = ['action' => $ability[0], 'subject' => $ability[1]];
        }
        // $success['ability'][] = ['action' => 'manage', 'subject' => 'all'];
        $success['fullname'] =  $authUser->name.' '.$authUser->surname;
        $success['username'] = $authUser->username;
        $success['email'] = $authUser->email;
        $success['role'] = $roles && isset($roles[0]) ? $roles[0] : 'admin';
        $success['avatar'] = $authUser->profile_photo_path;

        return $this->sendResponse(
            $success,
            trans('Obtenido con éxito')
        );
    }
}

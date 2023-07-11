<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{
    public function getRoles() {
        try {
            $roles = Role::select('roles.*', 'id as value', 'name as label')->get();

            foreach ($roles as $role) {
                $roleData = [
                    'label' => $role->label,
                    'value' => $role->value,
                    'permissions' => []
                ];

                foreach ($role->permissions as $permission) {
                    $roleData['permissions'][] = $permission->name;
                }

                $role->permissions = $roleData;
            }

            return $roles;

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getRole($id) {
        $role = Role::select('roles.*', 'id as value', 'name as label')->where('id', $id)->first();
        if ($role) {
            $roleData = [
                'label' => $role->label,
                'value' => $role->value,
                'permissions' => []
            ];

            foreach ($role->permissions as $permission) {
                $roleData['permissions'][] = $permission->name;
            }

            $role->permissions = $roleData;
            return response()->json([
                'status' => 200,
                'role' => $role
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Alumno no existe'
        ]);
    }

    public function create(Request $request){
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);
            $role->permissions()->sync($request->permissions);
            $role = Role::select('roles.*', 'id as value', 'name as label')->where('id', $role->id)->first();
            if ($role) {
                $roleData = [
                    'label' => $role->label,
                    'value' => $role->value,
                    'permissions' => []
                ];

                foreach ($role->permissions as $permission) {
                    $roleData['permissions'][] = $permission->name;
                }

                $role->permissions = $roleData;
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'role' => $role
        ]);
    }

    public function edit($id, Request $request){
        try {
            $role = Role::find($id);
            $role->update([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);
            $role->permissions()->sync($request->permissions);
            $role = Role::select('roles.*', 'id as value', 'name as label')->where('id', $role->id)->first();
            if ($role) {
                $roleData = [
                    'label' => $role->label,
                    'value' => $role->value,
                    'permissions' => []
                ];

                foreach ($role->permissions as $permission) {
                    $roleData['permissions'][] = $permission->name;
                }

                $role->permissions = $roleData;
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'role' => $role
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'has_commission' => $data['has_commission'],
            'commission' => $data['commission'] ?? 0.0,
            'active' => $data['active'],
            'teacher_id' => $data['teacher_id'] ?? null,
            'advisor_id' => $data['advisor_id'] ?? null,
            'default_password' => $data['default_password'] ?? null,
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);

        $roles = [$data['roles']];
        $user->syncRoles($roles);
        return $user;
    }

    public function update(User $user, array $data)
    {
        $user->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'has_commission' => $data['has_commission'],
            'commission' => $data['commission'] ?? 0.0,
            'active' => $data['active'],
            'teacher_id' => $data['teacher_id'] ?? null,
        ]);

        if ($data['active'] == 0) {

        }

        $roles = [$data['roles']];
        $user->syncRoles($roles[0]);
        return $user;
    }
}

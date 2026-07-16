<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            // Otros campos necesarios para tu usuario
            'surname' => 'AdminSurname',
            'username' => 'admin_username',
            'has_commission' => false,
            'active' => true,
            'teacher_id' => null,
        ]);

        // Asignar rol de administrador
        $admin->assignRole('Admin');
    }
}
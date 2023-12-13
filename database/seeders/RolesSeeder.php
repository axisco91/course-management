<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'Admin', 'guard_name' => 'web', 'created_at' => '2022-04-25 22:05:05', 'updated_at' => '2022-04-25 22:05:05'],
            ['name' => 'Docente', 'guard_name' => 'web', 'created_at' => '2022-06-13 09:55:09', 'updated_at' => '2022-06-13 09:55:09'],
            ['name' => 'Consultor', 'guard_name' => 'web', 'created_at' => '2023-07-08 10:53:28', 'updated_at' => '2023-09-18 22:35:25'],
            ['name' => 'Practicas', 'guard_name' => 'web', 'created_at' => '2023-09-20 21:50:47', 'updated_at' => '2023-09-20 21:50:47'],
            ['name' => 'Administración', 'guard_name' => 'web', 'created_at' => '2023-10-09 22:59:43', 'updated_at' => '2023-10-09 22:59:43']      
        ];  
        
         // Insertar los datos en la tabla
         DB::table('roles')->insert($datos);
    }
}

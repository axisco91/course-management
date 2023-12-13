<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class company_typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'Autónomo'],
            ['name' => 'Pyme'],
            ['name' => 'Gran Empresa']
        ];  
        
         // Insertar los datos en la tabla
         DB::table('company_types')->insert($datos);
    }
}

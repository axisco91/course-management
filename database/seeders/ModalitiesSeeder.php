<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ModalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'Presencial'],
            ['name' => 'Teleformación'],
            ['name' => 'Mixta']
        ];  
        
         // Insertar los datos en la tabla
         DB::table('modalities')->insert($datos);
    }
}

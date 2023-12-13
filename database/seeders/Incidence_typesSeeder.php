<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Incidence_typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'EMAIL'],
            ['name' => 'LLAMADA'],
            ['name' => 'VISITA'],
            ['name' => 'WHATSAPP']
        ];
        // Insertar los datos en la tabla
        DB::table('incidence_types')->insert($datos);
    }
}

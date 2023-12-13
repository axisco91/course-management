<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class Professional_categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'DIRECTIVO'],
            ['name' => 'MANDO INTERMEDIO'],
            ['name' => 'TÉCNICO'],
            ['name' => 'TRABAJADOR CON BAJA CUALIFICACIÓN'],
            ['name' => 'TRABAJADOR CUALIFICADO']
        ];
        // Insertar los datos en la tabla
        DB::table('professional_categories')->insert($datos);
    }
}

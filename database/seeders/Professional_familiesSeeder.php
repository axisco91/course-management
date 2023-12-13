<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class Professional_familiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'ADMINISTRACIÓN Y GESTIÓN'],
            ['name' => 'ADMINISTRACIÓN Y GESTIÓN, HOSTELERÍA Y TURISMO'],
            ['name' => 'AGRARIA'],
            ['name' => 'ARTES GRÁFICAS'],
            ['name' => 'COMERCIO Y MARKETING'],
            ['name' => 'COMERCIO Y MARKETING, ADMINISTRACIÓN Y GESTIÓN'],
            ['name' => 'EDIFICACIÓN Y OBRA CIVIL'],
            ['name' => 'ELECTRICIDAD Y ELECTRÓNICA'],
            ['name' => 'ENERGÍA Y AGUA'],
            ['name' => 'FABRICACIÓN MECÁNICA'],
            ['name' => 'FORMACIÓN COMPLEMENTARIA'],
            ['name' => 'HOSTELERÍA Y TURISMO'],
            ['name' => 'IDIOMAS'],
            ['name' => 'IMAGEN PERSONAL'],
            ['name' => 'IMAGEN Y SONIDO'],
            ['name' => 'INDUSTRIAS ALIMENTARIAS'],
            ['name' => 'INFORMÁTICA Y COMUNICACIONES'],
            ['name' => 'INSTALACIÓN Y MANTENIMIENTO'],
            ['name' => 'PREVENCIÓN DE RIESGOS LABORALES'],
            ['name' => 'SANIDAD'],
            ['name' => 'SEGURIDAD Y MEDIO AMBIENTE'],
            ['name' => 'SERVICIOS SOCIOCULTURALES Y A LA COMUNIDAD'],
            ['name' => 'SOFT SKILLS'],
            ['name' => 'TRANSPORTE Y MANTENIMIENTO DE VEHÍCULOS'],
            ['name' => 'VIDRIO Y CERÁMICA'],
        ];
               
         // Insertar los datos en la tabla
         DB::table('professional_families')->insert($datos);
    }
}

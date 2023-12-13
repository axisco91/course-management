<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class quote_groupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'GRUPO 1: Ingenieros y licenciados'],
            ['name' => 'GRUPO 2: Ingenieros Técnicos, Peritos y Ayudantes Titulados'],
            ['name' => 'GRUPO 3: Jefes Administrativos y de Taller'],
            ['name' => 'GRUPO 4: Ayudantes no Titulados'],
            ['name' => 'GRUPO 5: Oficiales Administrativos'],
            ['name' => 'GRUPO 6: Subalternos'],
            ['name' => 'GRUPO 7: Auxiliares Administrativos'],
            ['name' => 'GRUPO 8: Oficiales de primera y segunda'],
            ['name' => 'GRUPO 9: Oficiales de tercera y Especialistas'],
            ['name' => 'GRUPO 10: Peones'],
            ['name' => 'GRUPO 11: Trabajadores menores de dieciocho años, cualquiera que sea su categoría profesional'],
        ];
                
         // Insertar los datos en la tabla
         DB::table('quote_groups')->insert($datos);
    }
}

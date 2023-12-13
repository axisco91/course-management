<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class company_activitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'A - Agricultura, ganadería, silvicultura y pesca'],
            ['name' => 'B - Industrias extractivas'],
            ['name' => 'C - Industria manufacturera'],
            ['name' => 'D - Suministro de energía eléctrica, gas, vapor y aire acondicionado'],
            ['name' => 'E - Suministro de agua, actividades de saneamiento, gestión de residuos y descontaminación'],
            ['name' => 'F - Construcción'],
            ['name' => 'G - Comercio al por mayor y al por menor; reparación de vehículos de motor y motocicletas'],
            ['name' => 'H - Transporte y almacenamiento'],
            ['name' => 'I - Hostelería'],
            ['name' => 'J - Información y comunicaciones'],
            ['name' => 'K - Actividades financieras y de seguros'],
            ['name' => 'L - Actividades inmobiliarias'],
            ['name' => 'M - Actividades profesionales, científicas y técnicas'],
            ['name' => 'N - Actividades administrativas y servicios auxiliares'],
            ['name' => 'O - Administración Pública y defensa; Seguridad Social obligatoria'],
            ['name' => 'P - Educación'],
            ['name' => 'Q - Actividades sanitarias y de servicios sociales'],
            ['name' => 'R - Actividades artísticas, recreativas y de entretenimiento'],
            ['name' => 'S - Otros servicios'],
            ['name' => 'T - Actividades de los hogares como empleadores de personal doméstico; actividades de los hogares como productores de bienes y servicios para uso propio'],
            ['name' => 'U - Actividades de organizaciones y organismos extraterritoriales'],
        ];
        
        
         // Insertar los datos en la tabla
         DB::table('company_activities')->insert($datos);
    }
}

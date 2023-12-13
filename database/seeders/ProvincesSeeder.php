<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'ALBACETE'],
            ['name' => 'ALICANTE/ALACANT'],
            ['name' => 'ALMERIA'],
            ['name' => 'ARABA/ALABA'],
            ['name' => 'ASTURIAS'],
            ['name' => 'AVILA'],
            ['name' => 'BADAJOZ'],
            ['name' => 'BALEARS, ILLES'],
            ['name' => 'BARCELONA'],
            ['name' => 'BIZKAIA'],
            ['name' => 'BURGOS'],
            ['name' => 'CACERES'],
            ['name' => 'CADIZ'],
            ['name' => 'CANTANBRIA'],
            ['name' => 'CASTELLON'],
            ['name' => 'CIUDAD REAL'],
            ['name' => 'CORDOBA'],
            ['name' => 'CORUÑA, A'],
            ['name' => 'CUENCA'],
            ['name' => 'GIPUZKOA'],
            ['name' => 'GIRONA'],
            ['name' => 'GRANADA'],
            ['name' => 'GUADALAJARA'],
            ['name' => 'HUELVA'],
            ['name' => 'HUESCA'],
            ['name' => 'JAEN'],
            ['name' => 'LEON'],
            ['name' => 'LLEIDA'],
            ['name' => 'LUGO'],
            ['name' => 'MADRID'],
            ['name' => 'MALAGA'],
            ['name' => 'MURCIA'],
            ['name' => 'NAVARRA'],
            ['name' => 'OURENSE'],
            ['name' => 'PALENCIA'],
            ['name' => 'PALMAS, LAS'],
            ['name' => 'PONTEVEDRA'],
            ['name' => 'RIOJA, LA'],
            ['name' => 'SALAMANCA'],
            ['name' => 'SANTA CRUZ DE TENERIFE'],
            ['name' => 'SEGOVIA'],
            ['name' => 'SEVILLA'],
            ['name' => 'SORIA'],
            ['name' => 'TARRAGONA'],
            ['name' => 'TERUEL'],
            ['name' => 'TOLEDO'],
            ['name' => 'VALENCIA'],
            ['name' => 'VALLADOLID'],
            ['name' => 'ZAMORA'],
            ['name' => 'ZARAGOZA'],
            ['name' => 'CEUTA'],
            ['name' => 'MELILLA'],
        ];
        
        // Insertar los datos en la tabla
        DB::table('provinces')->insert($datos);
    }
}

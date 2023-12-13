<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $communities = [
            ['name' => 'Andalucía'],
            ['name' => 'Aragón'],
            ['name' => 'Asturias'],
            ['name' => 'Baleares'],
            ['name' => 'Canarias'],
            ['name' => 'Cantabria'],
            ['name' => 'Castilla-La Mancha'],
            ['name' => 'Castilla y León'],
            ['name' => 'Cataluña'],
            ['name' => 'Ceuta'],
            ['name' => 'Comunidad Valenciana'],
            ['name' => 'Extremadura'],
            ['name' => 'Galicia'],
            ['name' => 'La Rioja'],
            ['name' => 'Madrid'],
            ['name' => 'Melilla'],
            ['name' => 'Navarra'],
            ['name' => 'País Vasco'],
            ['name' => 'Región de Murcia']
        ];
      // Insertar los datos en la tabla
      DB::table('communities')->insert($communities);   
    }
}

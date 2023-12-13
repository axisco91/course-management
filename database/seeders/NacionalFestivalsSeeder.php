<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NacionalFestivalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'day' => '2024-01-01',
                'name' => 'Año Nuevo'
            ],
            [
                'day' => '2024-01-06',
                'name' => 'Reyes'
            ],
            [
                'day' => '2024-03-29',
                'name' => 'Viernes Santo'
            ],
            [
                'day' => '2024-05-01',
                'name' => 'Día del Trabajo'
            ],
            [
                'day' => '2024-08-15',
                'name' => 'Asunción de la Virgen'
            ],
            [
                'day' => '2024-10-12',
                'name' => 'Fiesta Nacional de España'
            ],
            [
                'day' => '2024-11-01',
                'name' => 'Todos los Santos'
            ],
            [
                'day' => '2024-12-06',
                'name' => 'Día de la Constitución'
            ],
            [
                'day' => '2024-12-08',
                'name' => 'Inmaculada Concepción'
            ],
            [
                'day' => '2024-12-25',
                'name' => 'Navidad'
            ],
            [
                'day' => '2025-01-01',
                'name' => 'Año Nuevo'
            ],
            [
                'day' => '2025-01-06',
                'name' => 'Día de Reyes'
            ],
            [
                'day' => '2025-04-18',
                'name' => 'Viernes Santo'
            ],
            [
                'day' => '2025-05-01',
                'name' => 'Día del Trabajo'
            ],
            [
                'day' => '2025-08-15',
                'name' => 'Asunción de la Virgen'
            ],
            [
                'day' => '2025-10-12',
                'name' => 'Fiesta Nacional de España'
            ],
            [
                'day' => '2025-11-01',
                'name' => 'Todos los Santos'
            ],
            [
                'day' => '2025-12-06',
                'name' => 'Día de la Constitución'
            ],
            [
                'day' => '2025-12-08',
                'name' => 'Inmaculada Concepción'
            ],
            [
                'day' => '2025-12-25',
                'name' => 'Navidad'
            ]
        ];

        foreach ($data as $item) {
            DB::table('nacional_festivals')->insert($item);
        }
    }
}
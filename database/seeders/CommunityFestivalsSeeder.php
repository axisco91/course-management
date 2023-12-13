<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunityFestivalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $festivals = [
            [
                'day' => '2024-02-28',
                'name' => 'Día de Andalucía',
                'community_id' => '1'
            ],
            [
                'day'=>'2024-04-23',
                'name' => 'Día de Aragón',
                'community_id' => '2'
            ],
            [
                'day'=>'2024-09-10',
                'name' => 'Día de Asturias',
                'community_id' => '3'
            ],
            [
                'day'=>'2024-03-1',
                'name' => 'Día de las Islas Baleares',
                'community_id' => '4'
            ],
            [
                'day'=>'2024-05-30',
                'name' => 'Día de Canarias',
                'community_id' => '5'
            ],
            [
                'day'=>'2024-07-28',
                'name' => 'Día de Cantabria',
                'community_id' => '6'
            ],
            [
                'day'=>'2024-05-31',
                'name' => 'Día de Castilla La Mancha',
                'community_id' => '7'
            ],
            [
                'day'=>'2024-04-23',
                'name' => 'Día de Castilla y León',
                'community_id' => '8'
            ],
            [
                'day'=>'2024-04-23',
                'name' => 'Día de Sant Jordi',
                'community_id' => '9'
            ],
            [
                'day'=>'2024-06-09',
                'name' => 'Día de Ceuta',
                'community_id' => '10'
            ],
            [
                'day'=>'2024-03-19',
                'name' => 'Día de San José',
                'community_id' => '11'
            ],
            [
                'day'=>'2024-09-08',
                'name' => 'Día de Extremadura',
                'community_id' => '12'
            ],
            [
                'day'=>'2024-07-25',
                'name' => 'Día de Galicia',
                'community_id' => '13'
            ],
            [
                'day'=>'2024-06-09',
                'name' => 'Día de La Rioja',
                'community_id' => '14'
            ],
            [
                'day'=>'2024-05-15',
                'name' => 'San Isidro Labrador',
                'community_id' => '15'
            ],
            [
                'day'=>'2024-09-17',
                'name' => 'Día de Melilla',
                'community_id' => '16'
            ],
            [
                'day'=>'2024-12-03',
                'name' => 'Día de Navarra',
                'community_id' => '17'
            ],
            [
                'day'=>'2024-10-25',
                'name' => 'Día de Euskadi',
                'community_id' => '18'
            ],
            [
                'day'=>'2024-06-09',
                'name' => 'Día de la Región de Murcia',
                'community_id' => '19'
            ],

        ];
        
         DB::table('community_festivals')->insert($festivals);
        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnLeaveType;

class OnLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //definos tipos de baja
        $bajas = [
            ['name' => 'Baja Voluntaria'],
            ['name' => 'Baja Despido'],
            ['name' => 'Baja Impago'],
            ['name' => 'Baja IT'],
        ];

        //Insertar datos en la bd
        foreach ($bajas as $baja){
            OnLeaveType::create($baja);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingContractStatus;

class TrainingContractStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'Tramitación'],
            ['name' => 'Impartición'],
            ['name' => 'Baja'],
            ['name' => 'Baja IT'],
            ['name' => 'No Formalizado'],
        ];
        foreach ($statuses as $status){
            TrainingContractStatus::create($status);
        }


    }
}

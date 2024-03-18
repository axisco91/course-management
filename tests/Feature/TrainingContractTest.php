<?php

namespace Tests\Feature;

use App\Models\TrainingContract;
use App\Models\TrainingContractExcludedDay;
use App\Models\TrainingContractFestival;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingContractTest extends TestCase
{
    use RefreshDatabase;

    public function testCalculateEndDates()
    {
        // Crea un contrato de formación
        $trainingContract = TrainingContract::create([
            'formative_hours_first_year' => 1000,
            'formative_hours_second_year' => 1000,
            'beginning_formation' => Carbon::now()->toDateString(),
            // ...
        ]);

        // Crea algunos días excluidos y festivales
        TrainingContractExcludedDay::create(['training_contract_id' => $trainingContract->id]);
        TrainingContractFestival::create(['training_contract_id' => $trainingContract->id]);

        // Llama al método calculateEndDates
        $response = $this->json('POST', '/api/training-contracts/calculate-end-dates/' . $trainingContract->id . '/8/8');

        // Verifica que la respuesta tenga un estado HTTP 200
        $response->assertStatus(200);

        // Verifica que las fechas de finalización se calculen correctamente
        $expectedEndFormation = Carbon::now()->addDays((1000 / 8) * 2 + 2)->toDateString();
        $expectedEndContract = Carbon::now()->addDays((1000 / 8) * 3 + 2)->toDateString();
        $response->assertJson([
            'end_formation' => $expectedEndFormation,
            'end_contract' => $expectedEndContract,
        ]);
    }
}
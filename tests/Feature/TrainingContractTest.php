<?php

namespace Tests\Feature;

use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractFestival;
use App\Models\TrainingContractsExcludedDay;
use App\Services\TrainingContractService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingContractTest extends TestCase
{
    use RefreshDatabase;

    private function makeTrainingContractPayload(array $overrides = []): array
    {
        return array_merge([
            'company_id' => 1,
            'student_id' => 1,
            'company_tutor' => 'Tutor',
            'company_tutor_dni' => '12345678A',
            'occupation_id' => null,
            'center_of_work' => 'Centro',
            'province_id' => null,
            'beginning' => '2026-03-04',
            'end' => '2027-03-03',
            'beginning_formation' => '2026-03-04',
            'end_formation' => null,
            'annually_day_hours' => 8,
            'bonus_hours_first_year' => 20,
            'bonus_hours_second_year' => 0,
            'training_schedule' => null,
            'working_hours' => null,
            'complete_schedule' => null,
            'training_contract_status_id' => null,
            'on_leave_type_id' => null,
            'on_leave_date' => null,
            'advisor_id' => null,
            'collaborator_id' => null,
            'percentage_first_year' => 35,
            'percentage_second_year' => 15,
            'formative_hours_first_year' => 20,
            'formative_hours_second_year' => 0,
            'provider_id' => null,
            'disabled' => 0,
            'youth_guarantee' => 0,
            'social_exclusion' => 0,
            'specialty' => 0,
            'professional_certificate' => 0,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => false,
            'sunday' => false,
            'observations' => null,
            'daily_hours_1' => 4,
            'daily_hours_2' => 0,
            'bonification' => false,
            'main_company_id' => null,
        ], $overrides);
    }

    public function testCalculateHoursKeepsCreatedCoursesFixedAndSchedulesNewElementsOnWorkingDays()
    {
        $trainingContract = TrainingContract::create([
            'company_id' => 1,
            'student_id' => 1,
            'beginning' => '2026-03-04',
            'end' => '2026-12-31',
            'beginning_formation' => '2026-03-04',
            'end_formation' => '2026-03-10',
            'bonus_hours_first_year' => 20,
            'bonus_hours_second_year' => 0,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => false,
            'sunday' => false,
            'main_company_id' => null,
        ]);

        $createdCourseAction = TrainingAction::create([
            'name' => 'Created course action',
            'formative_action' => 'Created course action',
            'total_hours' => 8,
        ]);

        $newAction = TrainingAction::create([
            'name' => 'New action',
            'formative_action' => 'New action',
            'total_hours' => 12,
        ]);

        TrainingContractElement::create([
            'training_contract_id' => $trainingContract->id,
            'training_action_id' => $createdCourseAction->id,
            'course_id' => 999,
            'order' => 1,
            'beginning' => '2026-03-04',
            'end' => '2026-03-05',
            'main_company_id' => null,
        ]);

        TrainingContractElement::create([
            'training_contract_id' => $trainingContract->id,
            'training_action_id' => $newAction->id,
            'order' => 2,
            'main_company_id' => null,
        ]);

        app(TrainingContractService::class)->calculateHours($trainingContract->fresh());

        $fixedElement = TrainingContractElement::orderBy('order')->first();
        $newElement = TrainingContractElement::orderBy('order')->skip(1)->first();

        $this->assertSame('2026-03-04', $fixedElement->beginning);
        $this->assertSame('2026-03-05', $fixedElement->end);
        $this->assertSame('2026-03-06', $newElement->beginning);
        $this->assertSame('2026-03-10', $newElement->end);
    }

    public function testCreateDefaultsSecondYearPercentageToZeroWhenFrontendOmitsIt()
    {
        $payload = $this->makeTrainingContractPayload();
        unset($payload['percentage_second_year']);

        $contract = app(TrainingContractService::class)->create($payload);

        $this->assertSame(0.0, (float) $contract->percentage_second_year);
    }

    public function testCreateReturnsExistingContractWhenSamePayloadIsSubmittedTwice()
    {
        $payload = $this->makeTrainingContractPayload();

        $first = app(TrainingContractService::class)->create($payload);
        $second = app(TrainingContractService::class)->create($payload);

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, TrainingContract::count());
    }

    public function testUpdateAllowsExplicitZeroForSecondYearPercentage()
    {
        $contract = TrainingContract::create($this->makeTrainingContractPayload([
            'number_cfa' => '0001',
        ]));

        app(TrainingContractService::class)->update($contract, $this->makeTrainingContractPayload([
            'percentage_second_year' => 0,
        ]));

        $this->assertSame(0.0, (float) $contract->fresh()->percentage_second_year);
    }

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
        TrainingContractsExcludedDay::create(['training_contract_id' => $trainingContract->id]);
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

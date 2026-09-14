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

    private function createToleranceScenario(): TrainingContract
    {
        $trainingContract = TrainingContract::create([
            'company_id' => 1,
            'student_id' => 1,
            'beginning' => '2026-03-04',
            'end' => '2026-12-31',
            'beginning_formation' => '2026-03-04',
            'end_formation' => '2026-03-06',
            'bonus_hours_first_year' => 12,
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

        foreach ([5, 5, 2] as $index => $hours) {
            $action = TrainingAction::create([
                'name' => "Tolerance action {$index}",
                'formative_action' => "Tolerance action {$index}",
                'total_hours' => $hours,
            ]);

            TrainingContractElement::create([
                'training_contract_id' => $trainingContract->id,
                'training_action_id' => $action->id,
                'order' => $index + 1,
                'main_company_id' => null,
            ]);
        }

        return $trainingContract;
    }

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

    public function testCalculateHoursNeverSchedulesElementsOnWeekendsEvenWhenEnabled()
    {
        $trainingContract = TrainingContract::create([
            'company_id' => 1,
            'student_id' => 1,
            'beginning' => '2026-08-01',
            'end' => '2026-12-31',
            'beginning_formation' => '2026-08-01',
            'end_formation' => '2026-08-04',
            'bonus_hours_first_year' => 8,
            'bonus_hours_second_year' => 0,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => true,
            'sunday' => true,
            'main_company_id' => null,
        ]);

        $action = TrainingAction::create([
            'name' => 'Weekend action',
            'formative_action' => 'Weekend action',
            'total_hours' => 8,
        ]);

        TrainingContractElement::create([
            'training_contract_id' => $trainingContract->id,
            'training_action_id' => $action->id,
            'order' => 1,
            'main_company_id' => null,
        ]);

        app(TrainingContractService::class)->calculateHours($trainingContract->fresh());

        $element = TrainingContractElement::first();

        $this->assertSame('2026-08-03', $element->beginning);
        $this->assertSame('2026-08-04', $element->end);
        $this->assertSame(2, $trainingContract->fresh()->total_days);
    }

    public function testToleranceCompactsFiveFiveAndTwoHoursIntoThreeDays()
    {
        $trainingContract = $this->createToleranceScenario();

        app(TrainingContractService::class)->calculateHours($trainingContract->fresh(), 25);

        $elements = TrainingContractElement::orderBy('order')->get();

        $this->assertSame(['2026-03-04', '2026-03-05', '2026-03-06'], $elements->pluck('beginning')->all());
        $this->assertSame(['2026-03-04', '2026-03-05', '2026-03-06'], $elements->pluck('end')->all());
        $this->assertSame([5.0, 5.0, 2.0], $elements->map(fn ($element) => (float) $element->training_action->total_hours)->all());
        $this->assertSame(12.0, (float) $trainingContract->fresh()->formation_hours);
        $this->assertSame([1, 1, 1], $result['updated_elements']->pluck('assigned_training_days')->all());
        $this->assertSame([4.0, 4.0, 4.0], $result['updated_elements']->pluck('assigned_hours')->all());
    }

    public function testToleranceAcceptsTheExactRemainderPercentageBoundary()
    {
        $trainingContract = $this->createToleranceScenario();

        app(TrainingContractService::class)->calculateHours($trainingContract->fresh(), 20);

        $this->assertSame('2026-03-06', TrainingContractElement::orderBy('order')->get()->last()->end);
    }

    public function testToleranceCompactsOnlyTheCoursesNeededToFillTheAvailablePeriod()
    {
        $trainingContract = TrainingContract::create([
            'company_id' => 1,
            'student_id' => 1,
            'beginning' => '2026-03-02',
            'end' => '2026-12-31',
            'beginning_formation' => '2026-03-02',
            'end_formation' => '2026-03-13',
            'bonus_hours_first_year' => 40,
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

        foreach (range(1, 8) as $order) {
            $action = TrainingAction::create([
                'name' => "Adaptive action {$order}",
                'formative_action' => "Adaptive action {$order}",
                'total_hours' => 5,
            ]);
            TrainingContractElement::create([
                'training_contract_id' => $trainingContract->id,
                'training_action_id' => $action->id,
                'order' => $order,
                'main_company_id' => null,
            ]);
        }

        $result = app(TrainingContractService::class)->calculateHours($trainingContract->fresh(), 25);

        $this->assertSame(6, $result['compacted_courses']);
        $this->assertSame(6, $result['compacted_days']);
        $this->assertSame(20.0, (float) $result['effective_tolerance_percentage']);
        $this->assertNull($result['adjustment_warning']);
        $this->assertSame('2026-03-13', TrainingContractElement::orderBy('order')->get()->last()->end);
    }

    public function testToleranceProtectsShortCoursesAndCompactsLongCoursesFirst()
    {
        $trainingContract = TrainingContract::create([
            'company_id' => 1,
            'student_id' => 1,
            'beginning' => '2026-03-02',
            'end' => '2026-12-31',
            'beginning_formation' => '2026-03-02',
            'end_formation' => '2026-03-13',
            'bonus_hours_first_year' => 100,
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

        foreach ([15, 85] as $index => $hours) {
            $action = TrainingAction::create([
                'name' => "Priority action {$hours}",
                'formative_action' => "Priority action {$hours}",
                'total_hours' => $hours,
            ]);
            TrainingContractElement::create([
                'training_contract_id' => $trainingContract->id,
                'training_action_id' => $action->id,
                'order' => $index + 1,
                'main_company_id' => null,
            ]);
        }

        $result = app(TrainingContractService::class)->calculateHours($trainingContract->fresh(), 40);
        $elements = TrainingContractElement::orderBy('order')->get();

        $this->assertSame(1, $result['compacted_courses']);
        $this->assertSame('2026-03-02', $elements[0]->beginning);
        $this->assertSame('2026-03-03', $elements[0]->end);
        $this->assertSame('2026-03-13', $elements[1]->end);
    }

    public function testToleranceDoesNotRemoveSeveralDaysWhenOneDayIsEnough()
    {
        $trainingContract = TrainingContract::create([
            'company_id' => 1,
            'student_id' => 1,
            'beginning' => '2026-03-02',
            'end' => '2026-12-31',
            'beginning_formation' => '2026-03-02',
            'end_formation' => '2026-04-09',
            'bonus_hours_first_year' => 100,
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

        foreach ([60, 40] as $index => $hours) {
            $action = TrainingAction::create([
                'name' => "Partial day action {$hours}",
                'formative_action' => "Partial day action {$hours}",
                'total_hours' => $hours,
            ]);
            TrainingContractElement::create([
                'training_contract_id' => $trainingContract->id,
                'training_action_id' => $action->id,
                'order' => $index + 1,
                'main_company_id' => null,
            ]);
        }

        $result = app(TrainingContractService::class)->calculateHours($trainingContract->fresh(), 25);
        $elements = TrainingContractElement::orderBy('order')->get();

        $this->assertSame(1, $result['compacted_courses']);
        $this->assertSame('2026-03-02', $elements[0]->beginning);
        $this->assertSame('2026-03-24', $elements[0]->end);
        $this->assertSame('2026-03-25', $elements[1]->beginning);
        $this->assertSame('2026-04-09', $elements[1]->end);
    }

    public function testAssignedHoursUseTheValidDailyRateWhenDatesCrossIntoAZeroHourSecondYear()
    {
        $trainingContract = TrainingContract::create([
            'company_id' => 1,
            'student_id' => 1,
            'beginning' => '2026-06-01',
            'end' => '2027-06-30',
            'beginning_formation' => '2026-06-01',
            'end_formation' => '2027-06-30',
            'daily_hours_1' => 3.49,
            'daily_hours_2' => 0,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => false,
            'sunday' => false,
            'main_company_id' => null,
        ]);
        $element = TrainingContractElement::create([
            'training_contract_id' => $trainingContract->id,
            'order' => 1,
            'beginning' => '2027-05-17',
            'end' => '2027-06-16',
            'main_company_id' => null,
        ]);

        $elements = collect([$element]);
        app(TrainingContractService::class)->appendAssignedScheduleMetrics($trainingContract, $elements);

        $this->assertSame(23, $element->assigned_training_days);
        $this->assertSame(80.27, (float) $element->assigned_hours);
    }

    public function testInsufficientToleranceRollsBackAllCalculatedDates()
    {
        $trainingContract = $this->createToleranceScenario();

        try {
            app(TrainingContractService::class)->calculateHours($trainingContract->fresh(), 19);
            $this->fail('Expected the itinerary calculation to fail.');
        } catch (\DomainException $exception) {
            $this->assertStringContainsString('El itinerario no cabe', $exception->getMessage());
        }

        $this->assertSame([null, null, null], TrainingContractElement::orderBy('order')->pluck('beginning')->all());
        $this->assertSame([null, null, null], TrainingContractElement::orderBy('order')->pluck('end')->all());
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

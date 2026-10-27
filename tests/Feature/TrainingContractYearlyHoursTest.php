<?php

namespace Tests\Feature;

use App\Models\TrainingContract;
use App\Services\TrainingContractService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TrainingContractYearlyHoursTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        // Isolate these service tests from unrelated application migrations.
        Schema::create('training_contracts', function (Blueprint $table) {
            $table->increments('id');
            foreach (array_keys($this->data()) as $column) {
                $table->string($column)->nullable();
            }
            foreach (['number_cfa', 'total_hours', 'original_training_contract_id'] as $column) {
                $table->string($column)->nullable();
            }
        });
        $migration = require database_path('migrations/2026_10_26_000001_add_yearly_hours_to_training_contracts_table.php');
        $migration->up();
        $migration = require database_path('migrations/2026_10_27_000001_add_external_second_year_to_training_contracts.php');
        $migration->up();
    }

    private function data(array $overrides = []): array
    {
        $data = array_fill_keys([
            'company_tutor', 'company_tutor_dni', 'occupation_id', 'center_of_work',
            'province_id', 'training_schedule', 'working_hours', 'complete_schedule',
            'training_contract_status_id', 'on_leave_type_id', 'on_leave_date',
            'advisor_id', 'collaborator_id', 'provider_id', 'disabled', 'youth_guarantee',
            'social_exclusion', 'specialty', 'professional_certificate', 'observations',
            'remuneration', 'remuneration_period', 'annual_holidays', 'bonification',
            'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
        ], null);

        return array_merge($data, [
            'company_id' => 1, 'student_id' => 1, 'main_company_id' => 1,
            'beginning' => '2026-01-01', 'end' => '2027-12-31',
            'beginning_formation' => '2026-01-01', 'end_formation' => '2027-12-31',
            'annually_day_hours' => 2250,
            'percentage_first_year' => 50, 'percentage_second_year' => 25,
            'bonus_hours_first_year' => 1125, 'bonus_hours_second_year' => 565,
            'formative_hours_first_year' => 225, 'formative_hours_second_year' => 450,
            'daily_hours_1' => 0, 'daily_hours_2' => 0,
        ], $overrides);
    }

    public function test_distribution_is_calculated_saved_and_reopened_without_changing_real_hours(): void
    {
        $service = app(TrainingContractService::class);
        $data = $this->data(['hours_first_year' => 450, 'hours_second_year' => 1800]);
        $contract = $service->create($data)->fresh();
        $this->assertEquals(450, $contract->hours_first_year);
        $this->assertEquals(1800, $contract->hours_second_year);
        $this->assertEquals(225, $contract->bonus_hours_first_year);
        $this->assertEquals(450, $contract->bonus_hours_second_year);
        $this->assertEquals(675, $contract->total_hours);
        $service->update($contract, array_merge($data, ['hours_first_year' => 500]));
        $contract->refresh();
        $this->assertEquals(250, $contract->bonus_hours_first_year);
        $this->assertEquals(225, $contract->formative_hours_first_year);
        $this->assertEquals(450, $contract->formative_hours_second_year);
        $this->assertEquals(0, $contract->daily_hours_1);
    }

    public function test_legacy_contracts_keep_saved_results_and_partial_distribution_uses_legacy_for_other_year(): void
    {
        $service = app(TrainingContractService::class);
        $data = $this->data();
        $contract = $service->create($data);
        $service->update($contract, $data);
        $contract->refresh();
        $this->assertNull($contract->hours_first_year);
        $this->assertNull($contract->hours_second_year);
        $this->assertEquals(1125, $contract->bonus_hours_first_year);
        $this->assertEquals(565, $contract->bonus_hours_second_year);
        $service->update($contract, array_merge($data, ['hours_first_year' => 450]));
        $this->assertEquals(225, $contract->fresh()->bonus_hours_first_year);
        $this->assertEquals(565, $contract->fresh()->bonus_hours_second_year);
    }

    public function test_second_year_keeps_rounding_to_five_and_zero_is_an_explicit_base(): void
    {
        $contract = app(TrainingContractService::class)->create($this->data([
            'hours_first_year' => 0, 'hours_second_year' => 2250,
        ]));
        $this->assertEquals(0, $contract->bonus_hours_first_year);
        $this->assertEquals(565, $contract->bonus_hours_second_year);
    }

    public function test_closed_first_year_cannot_be_overwritten(): void
    {
        $service = app(TrainingContractService::class);
        $root = $service->create($this->data());
        $renewal = $service->create($this->data([
            'beginning' => '2027-01-01', 'hours_first_year' => 450, 'hours_second_year' => 1800,
        ]));
        $renewal->update(['original_training_contract_id' => $root->id]);
        $service->update($renewal, $this->data([
            'hours_first_year' => 999, 'hours_second_year' => 1600,
            'percentage_first_year' => 100, 'formative_hours_first_year' => 999,
        ]));
        $renewal->refresh();
        $this->assertEquals(450, $renewal->hours_first_year);
        $this->assertEquals(225, $renewal->bonus_hours_first_year);
        $this->assertEquals(50, $renewal->percentage_first_year);
        $this->assertEquals(225, $renewal->formative_hours_first_year);
        $this->assertEquals(400, $renewal->bonus_hours_second_year);
    }

    public function test_external_second_year_can_be_created_without_original_contract_and_rounds_second_year_hours(): void
    {
        $service = app(TrainingContractService::class);
        $contract = $service->create($this->data([
            'external_second_year' => '1', 'beginning' => '2026-10-15', 'end' => '2027-10-14',
            'beginning_formation' => '2026-10-15', 'end_formation' => '2027-10-14',
            'hours_first_year' => 0, 'hours_second_year' => 1758, 'percentage_second_year' => 15,
        ]));
        $this->assertTrue($contract->fresh()->external_second_year);
        $this->assertNull($contract->original_training_contract_id);
        $this->assertEquals(265, $contract->bonus_hours_second_year);
    }

    /** @dataProvider invalidBases */
    public function test_invalid_bases_are_rejected(string $field, $value): void
    {
        $this->expectException(ValidationException::class);
        app(TrainingContractService::class)->create($this->data([$field => $value]));
    }

    public static function invalidBases(): array
    {
        return [['hours_first_year', -1], ['hours_second_year', -1], ['hours_first_year', 'abc']];
    }
}

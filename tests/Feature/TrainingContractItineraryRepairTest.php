<?php

namespace Tests\Feature;

use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Services\TrainingContractLinkService;
use App\Services\TrainingContractService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TrainingContractItineraryRepairTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        DB::connection()->getPdo()->sqliteCreateFunction('CONCAT', fn (...$parts) => implode('', $parts));
        Schema::create('training_contracts', function (Blueprint $table) {
            $table->increments('id');
            foreach (['main_company_id', 'company_id', 'student_id', 'original_training_contract_id', 'previous_training_contract_id'] as $column) {
                $table->unsignedInteger($column)->nullable();
            }
            $table->unique('previous_training_contract_id');
            $table->unsignedInteger('extension_sequence')->default(0);
            $table->boolean('external_second_year')->default(false);
            foreach (['beginning', 'end', 'beginning_formation', 'end_formation', 'number_cfa'] as $column) {
                $table->string($column)->nullable();
            }
            foreach (['bonus_hours_first_year', 'bonus_hours_second_year', 'formative_hours_first_year', 'formative_hours_second_year',
                'daily_hours_1', 'daily_hours_2', 'total_days', 'total_hours', 'formation_hours'] as $column) {
                $table->double($column)->default(0);
            }
            foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $column) {
                $table->integer($column)->default(0);
            }
            foreach (['hours_first_year', 'hours_second_year', 'percentage_first_year', 'percentage_second_year',
                'company_tutor', 'company_tutor_dni', 'occupation_id', 'center_of_work', 'remuneration', 'remuneration_period',
                'annual_holidays', 'province_id', 'annually_day_hours', 'training_schedule', 'working_hours', 'complete_schedule',
                'training_contract_status_id', 'on_leave_type_id', 'on_leave_date', 'advisor_id', 'collaborator_id', 'provider_id',
                'disabled', 'youth_guarantee', 'social_exclusion', 'specialty', 'professional_certificate', 'observations', 'bonification'] as $column) {
                $table->string($column)->nullable();
            }
        });
        Schema::create('training_contract_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            foreach (['training_contract_id', 'training_action_id', 'certification_id', 'course_id', 'main_company_id', 'order'] as $column) {
                $table->integer($column)->nullable();
            }
            $table->string('beginning')->nullable();
            $table->string('end')->nullable();
        });
        foreach (['training_actions', 'certifications'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('total_hours');
                $table->integer('certification_id')->nullable();
            });
        }
        Schema::create('training_contracts_excluded_days', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('training_contract_id');
            $table->integer('main_company_id');
            $table->integer('valid')->default(1);
            $table->string('day');
        });
        Schema::create('training_contract_festivals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('training_contract_id');
            $table->integer('main_company_id');
            foreach (['nacional', 'province', 'population', 'community'] as $type) {
                $table->integer($type . '_festival_id')->nullable();
            }
        });
        foreach (['nacional', 'province', 'population', 'community'] as $type) {
            Schema::create($type . '_festivals', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('day');
            });
        }
    }

    private function contract(array $attributes = []): TrainingContract
    {
        return TrainingContract::create(array_merge([
            'main_company_id' => 1, 'company_id' => 1355, 'student_id' => 361,
            'beginning' => '2026-10-15', 'end' => '2027-10-14',
            'beginning_formation' => '2026-10-15', 'end_formation' => '2027-10-14',
            'bonus_hours_first_year' => 0, 'bonus_hours_second_year' => 265,
            'formative_hours_first_year' => 0, 'formative_hours_second_year' => 266,
            'monday' => 1, 'tuesday' => 1, 'wednesday' => 1, 'thursday' => 1, 'friday' => 1,
        ], $attributes))->fresh();
    }

    private function original(array $attributes = []): TrainingContract
    {
        return $this->contract(array_merge([
            'beginning' => '2025-10-15', 'end' => '2026-10-14',
            'beginning_formation' => '2025-10-15', 'end_formation' => '2026-10-14',
        ], $attributes));
    }

    private function elements(TrainingContract $contract, array $hours = [40, 40, 40, 40, 40, 66]): void
    {
        foreach ($hours as $index => $value) {
            $id = DB::table('training_actions')->insertGetId(['total_hours' => $value]);
            TrainingContractElement::create([
                'training_contract_id' => $contract->id, 'training_action_id' => $id,
                'main_company_id' => 1, 'order' => $index,
            ]);
        }
    }

    public function test_unlinked_second_year_hours_fail_promptly_without_writes(): void
    {
        $contract = $this->contract();
        $this->elements($contract);
        $before = $contract->getAttributes();
        try {
            app(TrainingContractService::class)->calculateHours($contract, 25);
            $this->fail('Expected incompatible year validation.');
        } catch (\DomainException $e) {
            $this->assertStringContainsString('vincula', $e->getMessage());
        }
        $this->assertEquals($before, $contract->fresh()->getAttributes());
        $this->assertSame(0, TrainingContractElement::whereNotNull('beginning')->count());
        $this->assertSame(0, DB::transactionLevel());
    }

    public function test_link_preserves_data_and_calculates_second_year_with_excess(): void
    {
        $root = $this->original();
        $contract = $this->contract();
        $this->elements($contract);
        $before = $contract->getAttributes();
        $linked = app(TrainingContractLinkService::class)->link($contract, $root->id);
        $this->assertEquals($before, array_merge($linked->getAttributes(), [
            'original_training_contract_id' => null, 'previous_training_contract_id' => null, 'extension_sequence' => 0,
        ]));
        $this->assertEquals($root->id, $linked->original_training_contract_id);
        $result = app(TrainingContractService::class)->calculateHours($linked, 25);
        $this->assertEquals(0, $result['cont_days_first_year']);
        $this->assertGreaterThan(0, $result['cont_days_second_year']);
        $this->assertEquals(0, $result['formative_hours_first_year']);
        $this->assertEquals(266, $result['formative_hours_second_year']);
        $this->assertGreaterThan(0, $result['daily_hours_2']);
        $this->assertEquals('2027-10-14', $result['updated_elements']->last()->end);
        $this->assertSame(6, $result['updated_elements']->count());
        $previousEnd = null;
        foreach ($result['updated_elements'] as $element) {
            $this->assertNotNull($element->beginning);
            if ($previousEnd) $this->assertGreaterThan($previousEnd, $element->beginning);
            $previousEnd = $element->end;
        }
        $this->assertEquals(265, $linked->fresh()->bonus_hours_second_year);
    }

    public function test_first_year_and_cross_anniversary_distribution(): void
    {
        $service = app(TrainingContractService::class);
        $method = new \ReflectionMethod($service, 'distributeHours');
        $first = $method->invoke($service, 266, 265, 900, 231, 0, 231);
        $this->assertEquals(266, $first['formative_hours_first_year']);
        $this->assertEquals(0, $first['formative_hours_second_year']);
        $both = $method->invoke($service, 300, 100, 100, 100, 200, 300);
        $this->assertEqualsWithDelta(133.33, $both['formative_hours_first_year'], 0.001);
        $this->assertEqualsWithDelta(166.67, $both['formative_hours_second_year'], 0.001);
    }

    public function test_historical_first_year_metrics_and_bonus_are_preserved(): void
    {
        $root = $this->original();
        $contract = $this->contract(['bonus_hours_first_year' => 400, 'formative_hours_first_year' => 399, 'daily_hours_1' => 3.49]);
        $this->elements($contract);
        $linked = app(TrainingContractLinkService::class)->link($contract, $root->id);
        $result = app(TrainingContractService::class)->calculateHours($linked, 25);
        $this->assertEquals(0, $result['formative_hours_first_year']);
        $this->assertEquals(399, $linked->fresh()->formative_hours_first_year);
        $this->assertEquals(400, $linked->fresh()->bonus_hours_first_year);
        $this->assertEquals(3.49, $linked->fresh()->daily_hours_1);
    }

    public function test_invalid_links_are_rejected_without_changes(): void
    {
        $root = $this->original();
        foreach ([['student_id' => 999], ['company_id' => 999], ['main_company_id' => 2],
            ['beginning' => '2026-10-16'], ['end' => '2027-10-15']] as $attributes) {
            $contract = $this->contract($attributes);
            try {
                app(TrainingContractLinkService::class)->link($contract, $root->id);
                $this->fail('Expected invalid link.');
            } catch (ValidationException $e) {
                $this->assertNull($contract->fresh()->previous_training_contract_id);
            }
        }
    }

    public function test_duplicate_successor_self_link_and_relink_are_rejected(): void
    {
        $root = $this->original();
        $contract = $this->contract();
        $service = app(TrainingContractLinkService::class);
        try {
            $service->link($contract, $contract->id);
            $this->fail('Expected self link failure.');
        } catch (ValidationException $e) {
            $this->assertNull($contract->fresh()->previous_training_contract_id);
        }
        $service->link($contract, $root->id);
        foreach ([$contract, $this->contract()] as $candidate) {
            try {
                $service->link($candidate, $root->id);
                $this->fail('Expected duplicate/relink failure.');
            } catch (ValidationException $e) {
                $this->assertSame(1, $root->nextRenewal()->count());
            }
        }
    }

    public function test_missing_original_and_disabled_calendar_fail_without_writes(): void
    {
        $service = app(TrainingContractService::class);
        foreach ([['original_training_contract_id' => 999],
            ['monday' => 0, 'tuesday' => 0, 'wednesday' => 0, 'thursday' => 0, 'friday' => 0],
            ['end_formation' => '2026-01-01'], ['beginning_formation' => null],
            ['end_formation' => '2027-02-31']] as $attributes) {
            $contract = $this->contract($attributes);
            $before = $contract->getAttributes();
            try {
                $service->calculateHours($contract, 25);
                $this->fail('Expected validation failure.');
            } catch (\DomainException $e) {
                $this->assertEquals($before, $contract->fresh()->getAttributes());
            }
        }
    }

    public function test_no_end_date_with_zero_daily_hours_is_bounded(): void
    {
        $contract = $this->contract(['end_formation' => null]);
        $this->elements($contract);
        $this->expectException(\DomainException::class);
        app(TrainingContractService::class)->calculateHours($contract, 25);
    }

    public function test_holidays_exclusions_and_created_course_dates_are_respected(): void
    {
        $contract = $this->contract([
            'beginning' => '2026-10-19', 'beginning_formation' => '2026-10-19',
            'end' => '2026-10-23', 'end_formation' => '2026-10-23',
            'bonus_hours_first_year' => 12, 'bonus_hours_second_year' => 0,
        ]);
        $this->elements($contract, [4, 8]);
        $fixed = $contract->trainingContractElements()->orderBy('order')->first();
        $fixed->update(['course_id' => 100, 'beginning' => '2026-10-19', 'end' => '2026-10-19']);
        DB::table('training_contracts_excluded_days')->insert([
            'training_contract_id' => $contract->id, 'main_company_id' => 1, 'day' => '2026-10-20',
        ]);
        $festival = DB::table('nacional_festivals')->insertGetId(['name' => 'Holiday', 'day' => '2026-10-21']);
        DB::table('training_contract_festivals')->insert([
            'training_contract_id' => $contract->id, 'main_company_id' => 1, 'nacional_festival_id' => $festival,
        ]);
        $result = app(TrainingContractService::class)->calculateHours($contract, 0);
        $this->assertEquals(3, $result['total_days']);
        $this->assertEquals('2026-10-19', $fixed->fresh()->end);
        $this->assertEquals('2026-10-22', $result['updated_elements']->last()->beginning);
        $this->assertEquals('2026-10-23', $result['updated_elements']->last()->end);
    }

    public function test_insufficient_tolerance_rolls_back_contract_and_all_element_dates(): void
    {
        $contract = $this->contract([
            'beginning' => '2026-10-19', 'beginning_formation' => '2026-10-19',
            'end' => '2026-10-21', 'end_formation' => '2026-10-21',
            'bonus_hours_first_year' => 12, 'bonus_hours_second_year' => 0,
        ]);
        $this->elements($contract, [5, 5, 2]);
        $before = $contract->getAttributes();
        try {
            app(TrainingContractService::class)->calculateHours($contract, 0);
            $this->fail('Expected insufficient tolerance.');
        } catch (\DomainException $e) {
            $this->assertEquals($before, $contract->fresh()->getAttributes());
            $this->assertSame(0, TrainingContractElement::whereNotNull('beginning')->count());
        }
        $result = app(TrainingContractService::class)->calculateHours($contract->fresh(), 25);
        $this->assertEquals('2026-10-21', $result['updated_elements']->last()->end);
    }

    public function test_cycle_and_corrupt_chain_are_rejected(): void
    {
        $root = $this->original(['original_training_contract_id' => 999]);
        $current = $this->contract();
        $service = app(TrainingContractLinkService::class);
        try {
            $service->link($current, $root->id);
            $this->fail('Expected corrupt root failure.');
        } catch (ValidationException $e) {
            $this->assertNull($current->fresh()->previous_training_contract_id);
        }
        $root->update(['original_training_contract_id' => $root->id, 'previous_training_contract_id' => $root->id, 'extension_sequence' => 1]);
        $this->expectException(ValidationException::class);
        $service->link($current, $root->id);
    }

    private function apiUser(bool $canEdit = true): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('main_company_id');
            $table->integer('advisor_id')->nullable();
        });
        DB::table('users')->insert(['id' => 1, 'main_company_id' => 1]);
        $user = \Mockery::mock(\App\Models\User::class)->makePartial();
        $user->id = 1;
        $user->main_company_id = 1;
        $user->shouldReceive('can')->with('edit.training_contracts')->andReturn($canEdit);
        $this->withoutMiddleware();
        $this->actingAs($user);
    }

    public function test_api_candidates_are_filtered_and_permission_is_enforced(): void
    {
        $this->apiUser();
        $root = $this->original();
        $this->original(['student_id' => 999]);
        $current = $this->contract();
        $this->getJson('/api/training-contracts/' . $current->id . '/previous-candidates')
            ->assertOk()->assertJsonCount(1, 'data.candidates')->assertJsonPath('data.candidates.0.id', $root->id);
        $this->postJson('/api/training-contracts/' . $current->id . '/link-previous', ['previous_training_contract_id' => 999])
            ->assertStatus(422);
        $foreign = $this->contract(['main_company_id' => 2]);
        $this->getJson('/api/training-contracts/' . $foreign->id . '/previous-candidates')->assertNotFound();
    }

    public function test_api_link_actions_require_edit_permission(): void
    {
        $this->apiUser(false);
        $current = $this->contract();
        $this->getJson('/api/training-contracts/' . $current->id . '/previous-candidates')->assertForbidden();
        $this->postJson('/api/training-contracts/' . $current->id . '/link-previous', ['previous_training_contract_id' => 1])
            ->assertForbidden();
        $this->getJson('/api/training-contracts/calculate-hours/' . $current->id . '?tolerance_percentage=25')->assertForbidden();
    }

    public function test_api_link_returns_refreshed_chain_and_second_year_metadata(): void
    {
        $this->apiUser();
        foreach (['company', 'student', 'trainingContractStatus', 'provider', 'province', 'onLeaveType', 'advisor', 'occupation'] as $relation) {
            $table = (new TrainingContract())->$relation()->getRelated()->getTable();
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name')->nullable();
                    $table->string('surname')->nullable();
                });
            }
        }
        $root = $this->original();
        $current = $this->contract();
        $this->elements($current);
        $this->postJson('/api/training-contracts/' . $current->id . '/link-previous', [
            'previous_training_contract_id' => $root->id,
        ])->assertOk()->assertJsonPath('data.training_contract.previous_contract_id', $root->id)
            ->assertJsonPath('data.training_contract.original_training_contract_id', $root->id)
            ->assertJsonPath('data.training_contract.is_second_year_contract', true);
        $this->assertSame(2, TrainingContract::count());
        $this->getJson('/api/training-contracts/calculate-hours/' . $current->id . '?tolerance_percentage=25')
            ->assertOk()->assertJsonPath('data.total_hours', 266)
            ->assertJsonPath('data.formative_hours_first_year', 0)
            ->assertJsonPath('data.formative_hours_second_year', 266)
            ->assertJsonCount(6, 'data.updated_elements');
        $this->postJson('/api/training-contracts/' . $current->id . '/link-previous', [
            'previous_training_contract_id' => $root->id,
        ])->assertStatus(422);
        $before = $current->fresh()->trainingContractElements()->get()->toArray();
        $this->postJson('/api/training-contracts/' . $current->id . '/unlink-previous', [
            'previous_training_contract_id' => $root->id, 'confirmed' => true,
        ])->assertOk()->assertJsonPath('data.training_contract.previous_contract_id', null)
            ->assertJsonPath('data.training_contract.extension_sequence', 0);
        $this->assertEquals($before, $current->trainingContractElements()->get()->toArray());
    }

    public function test_unlink_preserves_all_other_attributes_and_elements(): void
    {
        $root = $this->original();
        $contract = $this->contract();
        $service = app(TrainingContractLinkService::class);
        $linked = $service->link($contract, $root->id);
        $this->elements($linked);
        $before = $linked->getAttributes();
        $elements = $linked->trainingContractElements()->get()->toArray();
        $result = $service->unlink($linked, $root->id);
        $expected = array_merge($before, ['original_training_contract_id' => null, 'previous_training_contract_id' => null, 'extension_sequence' => 0]);
        $this->assertEquals($expected, $result->getAttributes());
        $this->assertEquals($elements, $result->trainingContractElements()->get()->toArray());
        $this->assertFalse($root->nextRenewal()->exists());
        $this->assertEquals($root->id, $service->link($result, $root->id)->previous_training_contract_id);
    }

    public function test_unlink_rejects_intermediate_contracts_stale_requests_and_independent_contracts(): void
    {
        $root = $this->original(['beginning' => '2026-01-01', 'end' => '2026-06-30']);
        $middle = $this->contract(['beginning' => '2026-07-01', 'end' => '2026-12-31']);
        $last = $this->contract(['beginning' => '2027-01-01', 'end' => '2027-12-31']);
        $service = app(TrainingContractLinkService::class);
        $middle = $service->link($middle, $root->id);
        $last = $service->link($last, $middle->id);
        foreach ([[$middle, $root->id], [$last, $root->id], [$root, $middle->id]] as [$contract, $previous]) {
            $before = $contract->fresh()->getAttributes();
            try {
                $service->unlink($contract, $previous);
                $this->fail('Expected unlink validation.');
            } catch (ValidationException $e) {
                $this->assertEquals($before, $contract->fresh()->getAttributes());
            }
        }
        $this->assertNull($service->unlink($last, $middle->id)->previous_training_contract_id);
        $this->assertNull($service->unlink($middle, $root->id)->previous_training_contract_id);
    }

    public function test_api_unlink_requires_permission_confirmation_and_same_organization(): void
    {
        $this->apiUser(false);
        $root = $this->original();
        $contract = app(TrainingContractLinkService::class)->link($this->contract(), $root->id);
        $payload = ['confirmed' => true, 'previous_training_contract_id' => $root->id];
        $url = '/api/training-contracts/' . $contract->id . '/unlink-previous';
        $this->postJson($url, $payload)->assertForbidden();
        $user = \Mockery::mock(\App\Models\User::class)->makePartial();
        $user->id = 1;
        $user->shouldReceive('can')->with('edit.training_contracts')->andReturn(true);
        $this->actingAs($user);
        $this->postJson($url, ['previous_training_contract_id' => $root->id])->assertStatus(422);
        $this->postJson($url, ['confirmed' => true, 'previous_training_contract_id' => 999])->assertStatus(422);
        $contract->update(['main_company_id' => 2]);
        $this->postJson($url, $payload)->assertNotFound();
        $this->assertEquals($root->id, $contract->fresh()->previous_training_contract_id);
    }

    public function test_api_calculation_returns_422_and_missing_contract_closes_transaction(): void
    {
        $this->apiUser();
        $current = $this->contract();
        $this->elements($current);
        $this->getJson('/api/training-contracts/calculate-hours/' . $current->id . '?tolerance_percentage=25')
            ->assertStatus(422)->assertJsonPath('status', 422);
        $this->assertSame(0, DB::transactionLevel());
        $this->getJson('/api/training-contracts/calculate-hours/999?tolerance_percentage=25')->assertNotFound();
        $this->assertSame(0, DB::transactionLevel());
    }

    public function test_external_second_year_uses_second_hours_preserves_history_and_course_identity(): void
    {
        $contract = $this->contract(['external_second_year' => true, 'bonus_hours_first_year' => 615,
            'formative_hours_first_year' => 615, 'daily_hours_1' => 2.66]);
        $this->elements($contract);
        $before = $contract->trainingContractElements()->orderBy('order')->get()->map->only(['id', 'training_action_id', 'certification_id', 'course_id'])->all();
        $result = app(TrainingContractService::class)->calculateHours($contract, 25);
        $this->assertEquals(0, $result['cont_days_first_year']);
        $this->assertEquals(266, $result['formative_hours_second_year']);
        $this->assertEquals(615, $contract->fresh()->formative_hours_first_year);
        $this->assertEquals(2.66, $contract->fresh()->daily_hours_1);
        $after = $contract->trainingContractElements()->orderBy('order')->get()->map->only(['id', 'training_action_id', 'certification_id', 'course_id'])->all();
        $this->assertEquals($before, $after);
        $this->assertNull($contract->fresh()->original_training_contract_id);
        $metrics = app(TrainingContractService::class)->recalculateFormativeHours($contract->fresh(), $result['updated_elements']);
        $this->assertEquals(0, $metrics['formative_hours_first_year']);
        $this->assertGreaterThan(0, $metrics['formative_hours_second_year']);
        $this->assertEquals(266, app(TrainingContractService::class)->getTotalHours($contract->id));
    }

    public function test_external_marker_save_and_disable_leave_courses_dates_and_calculated_hours_unchanged(): void
    {
        $contract = $this->contract(['hours_first_year' => 1758, 'percentage_first_year' => 35,
            'bonus_hours_first_year' => 615, 'formative_hours_first_year' => 615, 'daily_hours_1' => 2.66,
            'hours_second_year' => 1758, 'percentage_second_year' => 15, 'daily_hours_2' => 1.1]);
        $this->elements($contract);
        $element = $contract->trainingContractElements()->first();
        $element->update(['beginning' => '2026-11-02', 'end' => '2026-12-04', 'course_id' => 100]);
        $elementsBefore = $contract->trainingContractElements()->get()->toArray();
        $fields = ['formative_hours_first_year', 'formative_hours_second_year', 'daily_hours_1', 'daily_hours_2', 'beginning_formation', 'end_formation'];
        $before = $contract->only($fields);
        $service = app(TrainingContractService::class);
        $saved = $service->update($contract, array_merge($contract->getAttributes(), ['external_second_year' => true]));
        $this->assertTrue($saved->fresh()->external_second_year);
        $this->assertEquals($before, $saved->fresh()->only($fields));
        $this->assertEquals(265, $saved->fresh()->bonus_hours_second_year);
        $disabled = $service->update($saved, array_merge($saved->getAttributes(), ['external_second_year' => false]));
        $this->assertFalse($disabled->fresh()->external_second_year);
        $this->assertEquals($before, $disabled->fresh()->only($fields));
        $this->assertEquals($elementsBefore, $contract->trainingContractElements()->get()->toArray());
    }

    public function test_external_history_tampering_and_invalid_dates_return_validation_errors(): void
    {
        $contract = $this->contract(['external_second_year' => true, 'bonus_hours_first_year' => 615]);
        $service = app(TrainingContractService::class);
        foreach ([['bonus_hours_first_year' => 999], ['end' => '2027-10-15'],
            ['beginning_formation' => '2026-10-14'], ['end_formation' => '2027-10-15']] as $override) {
            try {
                $service->update($contract, array_merge($contract->getAttributes(), $override));
                $this->fail('Expected invalid external update.');
            } catch (ValidationException $e) {
                $this->assertEquals(615, $contract->fresh()->bonus_hours_first_year);
                $this->assertEquals('2027-10-14', $contract->fresh()->end);
            }
        }
    }

    public function test_external_period_cannot_be_linked_or_renewed_or_added_to_internal_chain(): void
    {
        $root = $this->original();
        $external = $this->contract(['external_second_year' => true]);
        foreach (['link', 'renew', 'enable'] as $operation) {
            try {
                if ($operation === 'link') app(TrainingContractLinkService::class)->link($external, $root->id);
                if ($operation === 'renew') app(\App\Services\TrainingContractRenewalService::class)->create($external, '2028-10-14');
                if ($operation === 'enable') {
                    $external->update(['external_second_year' => false]);
                    $linked = app(TrainingContractLinkService::class)->link($external, $root->id);
                    app(TrainingContractService::class)->update($linked, array_merge($linked->getAttributes(), ['external_second_year' => true]));
                }
                $this->fail('Expected conflict with internal chain.');
            } catch (ValidationException $e) {
                $this->assertNotEmpty($e->errors());
            }
        }
    }

    public function test_external_months_and_end_date_exclude_historical_year(): void
    {
        $contract = $this->contract(['external_second_year' => true,
            'beginning' => '2026-10-15', 'beginning_formation' => '2026-10-29', 'end_formation' => '2026-11-03',
            'daily_hours_1' => 8, 'daily_hours_2' => 2, 'formative_hours_first_year' => 1000, 'formative_hours_second_year' => 8]);
        $service = app(TrainingContractService::class);
        $monthly = $service->calculateMonthlyFormationHours($contract->id);
        $this->assertEquals(['2026-10' => 4, '2026-11' => 4], $monthly['monthly_formation_hours']);
        $this->assertEquals('2026-11-03', $service->externalEndFormation($contract, 2));
        $contract->update(['end_formation' => null]);
        $this->elements($contract, [8]);
        $result = $service->calculateHours($contract->fresh(), 25);
        $this->assertEquals(0, $result['cont_days_first_year']);
        $this->assertEquals('2026-11-03', $contract->fresh()->end_formation);
    }

    public function test_api_external_flag_change_requires_edit_and_validates_historical_values(): void
    {
        $this->apiUser(false);
        $contract = $this->contract();
        $this->putJson('/api/training-contracts/' . $contract->id, ['external_second_year' => true])->assertForbidden();
        $this->postJson('/api/training-contracts', ['external_second_year' => true])->assertForbidden();
        $this->assertFalse($contract->fresh()->external_second_year);
    }

    public function test_api_external_calculation_uses_second_year_without_a_previous_contract(): void
    {
        $this->apiUser();
        $contract = $this->contract(['external_second_year' => true, 'formative_hours_first_year' => 615]);
        $this->elements($contract);
        $this->getJson('/api/training-contracts/calculate-hours/' . $contract->id . '?tolerance_percentage=25')
            ->assertOk()->assertJsonPath('data.formative_hours_first_year', 0)->assertJsonPath('data.formative_hours_second_year', 266);
        $this->assertEquals(615, $contract->fresh()->formative_hours_first_year);
        $this->putJson('/api/training-contracts/' . $contract->id, ['external_second_year' => true, 'formative_hours_first_year' => 999])
            ->assertStatus(422)->assertJsonValidationErrors('formative_hours_first_year');
        $this->getJson('/api/training-contracts/' . $contract->id . '/previous-candidates')->assertStatus(422);
    }
}

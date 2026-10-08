<?php

namespace Tests\Feature;

use App\Models\TrainingContract;
use App\Services\TrainingContractCompletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TrainingContractCompletionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testOnlyAptoResultsFromTheSameStudentAndContractChainAreBlocked(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            $root = $this->createContract(10);
            $renewal = $this->createContract(10, $root->id, $root->id, 1);
            $otherRoot = $this->createContract(10);

            $this->addResult($root->id, 10, 7001, 'apto');
            $this->addResult($root->id, 10, 7002, 'no_apto');
            $this->addResult($otherRoot->id, 10, 7003, 'apto');
            $this->addResult($root->id, 11, 7004, 'apto');
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $ids = app(TrainingContractCompletionService::class)
            ->passedTrainingActionIds($renewal)
            ->all();

        $this->assertSame([7001], $ids);
    }

    public function testAptoSpecialtyCannotBeAddedToARenewal(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            $root = $this->createContract(10);
            $renewal = $this->createContract(10, $root->id, $root->id, 1);
            $this->addResult($root->id, 10, 7001, 'apto');
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->expectException(ValidationException::class);
        app(TrainingContractCompletionService::class)
            ->assertTrainingActionCanBeAdded($renewal, 7001);
    }

    private function createContract(
        int $studentId,
        ?int $rootId = null,
        ?int $previousId = null,
        int $sequence = 0
    ): TrainingContract {
        return TrainingContract::create([
            'company_id' => 20,
            'student_id' => $studentId,
            'beginning' => '2026-01-01',
            'end' => '2026-06-30',
            'beginning_formation' => '2026-01-01',
            'end_formation' => '2026-06-30',
            'original_training_contract_id' => $rootId,
            'previous_training_contract_id' => $previousId,
            'extension_sequence' => $sequence,
            'main_company_id' => null,
        ]);
    }

    private function addResult(int $contractId, int $studentId, int $trainingActionId, string $result): void
    {
        $courseId = DB::table('courses')->insertGetId([
            'name' => "Course {$trainingActionId}",
            'training_action_id' => $trainingActionId,
            'group' => 'TEST',
            'course_type_id' => 1,
            'nebrija' => 0,
            'outsourced' => 0,
            'main_company_id' => null,
        ]);
        $elementId = DB::table('training_contract_elements')->insertGetId([
            'training_contract_id' => $contractId,
            'training_action_id' => $trainingActionId,
            'course_id' => $courseId,
            'main_company_id' => null,
        ]);
        DB::table('tracings')->insert([
            'course_id' => $courseId,
            'company_id' => 20,
            'student_id' => $studentId,
            'training_contract_element_id' => $elementId,
            'suitability' => $result,
            'main_company_id' => null,
        ]);
    }
}

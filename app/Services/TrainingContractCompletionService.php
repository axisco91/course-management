<?php

namespace App\Services;

use App\Models\TrainingContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrainingContractCompletionService
{
    public function passedTrainingActionIds(TrainingContract $contract): Collection
    {
        if ((int) $contract->extension_sequence === 0) {
            return collect();
        }

        $rootId = (int) ($contract->original_training_contract_id ?: $contract->id);
        $chainIds = TrainingContract::query()
            ->where('main_company_id', $contract->main_company_id)
            ->where(function ($query) use ($rootId) {
                $query->whereKey($rootId)
                    ->orWhere('original_training_contract_id', $rootId);
            })
            ->pluck('id');

        return DB::table('tracings')
            ->join('courses', 'courses.id', '=', 'tracings.course_id')
            ->where('tracings.main_company_id', $contract->main_company_id)
            ->where('tracings.student_id', $contract->student_id)
            ->whereRaw('LOWER(tracings.suitability) = ?', ['apto'])
            ->whereNotNull('courses.training_action_id')
            ->whereExists(function ($query) use ($chainIds) {
                $query->selectRaw('1')
                    ->from('training_contract_elements')
                    ->whereIn('training_contract_elements.training_contract_id', $chainIds)
                    ->where(function ($elementQuery) {
                        $elementQuery
                            ->whereColumn('training_contract_elements.id', 'tracings.training_contract_element_id')
                            ->orWhereColumn('training_contract_elements.course_id', 'tracings.course_id');
                    });
            })
            ->distinct()
            ->pluck('courses.training_action_id')
            ->map(fn ($id) => (int) $id)
            ->values();
    }

    public function assertTrainingActionCanBeAdded(TrainingContract $contract, int $trainingActionId): void
    {
        if ($this->passedTrainingActionIds($contract)->contains($trainingActionId)) {
            throw ValidationException::withMessages([
                'id' => 'Esta especialidad ya fue superada con resultado APTO en esta cadena contractual.',
            ]);
        }
    }
}

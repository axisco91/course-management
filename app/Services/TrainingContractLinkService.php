<?php

namespace App\Services;

use App\Models\TrainingContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrainingContractLinkService
{
    public function assertIndependent(TrainingContract $contract): void
    {
        if ($contract->external_second_year) {
            $this->invalid('Un segundo año procedente de otro centro no puede vincularse a una cadena interna.');
        }
        if ($this->date($contract->end)->lt($this->date($contract->beginning))) {
            $this->invalid('Las fechas del contrato no son válidas.');
        }
        if ($contract->previous_training_contract_id || $contract->original_training_contract_id
            || (int) $contract->extension_sequence !== 0 || $contract->nextRenewal()->exists()
            || $contract->renewals()->exists()) {
            $this->invalid('Solo se puede vincular un contrato independiente, sin prórrogas posteriores.');
        }
    }

    public function validatePair(TrainingContract $current, TrainingContract $previous, bool $lock = false): TrainingContract
    {
        if ($current->id === $previous->id) {
            $this->invalid('Un contrato no puede vincularse consigo mismo.');
        }
        $visited = [$current->id => true];
        $node = $previous;
        $chain = [];
        while (true) {
            if (isset($visited[$node->id])) {
                $this->invalid('La cadena de contratos contiene un ciclo.');
            }
            $visited[$node->id] = true;
            if ($node->external_second_year) {
                $this->invalid('Un segundo año procedente de otro centro no puede formar parte de una cadena interna.');
            }
            foreach (['main_company_id', 'company_id', 'student_id'] as $field) {
                if ((string) $node->$field !== (string) $current->$field) {
                    $this->invalid('Los contratos deben pertenecer al mismo alumno, empresa y organización.');
                }
            }
            $chain[] = $node;
            if (!$node->previous_training_contract_id) {
                if ($node->original_training_contract_id || (int) $node->extension_sequence !== 0) {
                    $this->invalid('El contrato original tiene una vinculación inconsistente.');
                }
                $root = $node;
                break;
            }
            $query = TrainingContract::whereKey($node->previous_training_contract_id)
                ->where('main_company_id', $current->main_company_id);
            $node = ($lock ? $query->lockForUpdate() : $query)->first();
            if (!$node) {
                $this->invalid('No se encuentra el contrato anterior de la cadena.');
            }
        }
        $parent = null;
        foreach (array_reverse($chain) as $index => $node) {
            if ($index > 0 && ((int) $node->original_training_contract_id !== (int) $root->id
                || (int) $node->extension_sequence !== $index
                || $this->date($node->beginning)->ne($this->date($parent->end)->addDay()))) {
                $this->invalid('La cadena de contratos tiene fechas o vínculos inconsistentes.');
            }
            if ($this->date($node->end)->lt($this->date($node->beginning))) {
                $this->invalid('Las fechas de la cadena no son válidas.');
            }
            $parent = $node;
        }
        if ($previous->nextRenewal()->exists()) {
            $this->invalid('El contrato anterior ya tiene una prórroga.');
        }
        if ($this->date($current->beginning)->ne($this->date($previous->end)->addDay())) {
            $this->invalid('La prórroga debe comenzar al día siguiente de finalizar el contrato anterior.');
        }
        if ($this->date($current->end)->lt($this->date($current->beginning))
            || $this->date($current->end)->gt($this->date($root->beginning)->addYears(2)->subDay())) {
            $this->invalid('Las fechas no son válidas o la cadena supera los dos años.');
        }
        return $root;
    }

    public function link(TrainingContract $current, int $previousId): TrainingContract
    {
        return DB::transaction(function () use ($current, $previousId) {
            $contracts = TrainingContract::where('main_company_id', $current->main_company_id)
                ->whereIn('id', [$current->id, $previousId])->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $current = $contracts->get($current->id);
            $previous = $contracts->get($previousId);
            if (!$current || !$previous) {
                $this->invalid('No se encuentra el contrato anterior en esta organización.');
            }
            $this->assertIndependent($current);
            $root = $this->validatePair($current, $previous, true);
            $current->update([
                'original_training_contract_id' => $root->id,
                'previous_training_contract_id' => $previous->id,
                'extension_sequence' => (int) $previous->extension_sequence + 1,
            ]);
            return $current->fresh();
        }, 3);
    }

    public function unlink(TrainingContract $contract, int $expectedPreviousId): TrainingContract
    {
        return DB::transaction(function () use ($contract, $expectedPreviousId) {
            $current = TrainingContract::whereKey($contract->id)->where('main_company_id', $contract->main_company_id)
                ->lockForUpdate()->firstOrFail();
            if (!$current->previous_training_contract_id || !$current->original_training_contract_id
                || (int) $current->extension_sequence < 1) {
                $this->invalid('El contrato no es una prórroga vinculada.');
            }
            if ((int) $current->previous_training_contract_id !== $expectedPreviousId) {
                $this->invalid('La vinculación ha cambiado. Recarga el contrato antes de desvincularlo.');
            }
            if ($current->nextRenewal()->exists() || $current->renewals()->exists()) {
                $this->invalid('Solo se puede desvincular la última prórroga, sin contratos posteriores.');
            }
            $previous = TrainingContract::whereKey($expectedPreviousId)->where('main_company_id', $current->main_company_id)->first();
            if (!$previous || (string) $previous->student_id !== (string) $current->student_id
                || (string) $previous->company_id !== (string) $current->company_id) {
                $this->invalid('El contrato anterior no pertenece al mismo alumno, empresa y organización.');
            }
            $current->update([
                'previous_training_contract_id' => null,
                'original_training_contract_id' => null,
                'extension_sequence' => 0,
            ]);
            return $current->fresh();
        }, 3);
    }

    private function date($value): Carbon
    {
        if (!$value || !Carbon::hasFormat($value, 'Y-m-d')) {
            $this->invalid('Los contratos deben tener fechas de inicio y fin válidas.');
        }
        $date = Carbon::createFromFormat('!Y-m-d', $value);
        if ($date->format('Y-m-d') !== $value) {
            $this->invalid('Los contratos deben tener fechas de inicio y fin válidas.');
        }
        return $date;
    }

    private function invalid(string $message): never
    {
        throw ValidationException::withMessages(['previous_training_contract_id' => $message]);
    }
}

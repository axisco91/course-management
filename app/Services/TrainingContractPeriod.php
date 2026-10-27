<?php

namespace App\Services;

use App\Models\TrainingContract;
use Illuminate\Support\Carbon;

class TrainingContractPeriod
{
    public static function beginning(TrainingContract $contract): Carbon
    {
        $source = $contract;
        if ($contract->original_training_contract_id) {
            $source = $contract->originalContract()->where('main_company_id', $contract->main_company_id)->first();
            if (!$source || (string) $source->student_id !== (string) $contract->student_id
                || (string) $source->company_id !== (string) $contract->company_id) {
                throw new \DomainException('El contrato original no existe o no pertenece al mismo alumno, empresa y organización.');
            }
        }
        if (!$source->beginning || !Carbon::hasFormat($source->beginning, 'Y-m-d')
            || Carbon::createFromFormat('!Y-m-d', $source->beginning)->format('Y-m-d') !== $source->beginning) {
            throw new \DomainException('El contrato no tiene una fecha de inicio válida.');
        }
        return Carbon::parse($source->beginning)->startOfDay();
    }

    public static function firstYearLimit(TrainingContract $contract): Carbon
    {
        $beginning = self::beginning($contract);
        return $contract->external_second_year ? $beginning : $beginning->addYear();
    }

    public static function lastDay(TrainingContract $contract): Carbon
    {
        return self::beginning($contract)->addYears($contract->external_second_year ? 1 : 2)->subDay();
    }

    public static function isSecondYear(TrainingContract $contract): bool
    {
        return $contract->external_second_year || Carbon::parse($contract->beginning)->gte(self::firstYearLimit($contract));
    }
}

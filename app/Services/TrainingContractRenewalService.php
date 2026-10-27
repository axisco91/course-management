<?php

namespace App\Services;

use App\Models\TrainingContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrainingContractRenewalService
{
    public function create(TrainingContract $source, string $newEnd): TrainingContract
    {
        return DB::transaction(function () use ($source, $newEnd) {
            $source = TrainingContract::whereKey($source->id)->lockForUpdate()->firstOrFail();
            if ($source->external_second_year) {
                throw ValidationException::withMessages(['contract' => 'Un segundo año procedente de otro centro no admite nuevas prórrogas.']);
            }
            if ($source->nextRenewal()->exists()) {
                throw ValidationException::withMessages([
                    'contract' => 'Solo se puede crear una prórroga desde el último contrato de la cadena.',
                ]);
            }

            $root = $source->original_training_contract_id
                ? TrainingContract::whereKey($source->original_training_contract_id)->lockForUpdate()->firstOrFail()
                : $source;
            $start = Carbon::parse($source->end)->addDay()->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $newEnd)->startOfDay();
            $maximumEnd = Carbon::parse($root->beginning)->addYears(2)->subDay()->startOfDay();

            if ($end->lt($start)) {
                throw ValidationException::withMessages([
                    'end' => 'La fecha fin debe ser igual o posterior al inicio automático de la prórroga.',
                ]);
            }
            if ($end->gt($maximumEnd)) {
                throw ValidationException::withMessages([
                    'end' => 'La duración total de la cadena no puede superar los 2 años.',
                ]);
            }

            $statusId = DB::table('training_contract_statuses')
                ->whereRaw('UPPER(name) = ?', ['TRAMITACIÓN'])
                ->value('id');
            if (!$statusId) {
                throw ValidationException::withMessages([
                    'status' => 'No existe el estado Tramitación necesario para crear la prórroga.',
                ]);
            }

            $renewal = $source->replicate([
                'number_cfa',
                'beginning',
                'end',
                'beginning_formation',
                'end_formation',
                'training_contract_status_id',
                'on_leave_type_id',
                'on_leave_date',
                'formation_hours',
            ]);
            $renewal->fill([
                'number_cfa' => null,
                'original_training_contract_id' => $root->id,
                'previous_training_contract_id' => $source->id,
                'extension_sequence' => ((int) $source->extension_sequence) + 1,
                'beginning' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'beginning_formation' => $start->format('Y-m-d'),
                'end_formation' => $end->format('Y-m-d'),
                'training_contract_status_id' => $statusId,
                'on_leave_type_id' => null,
                'on_leave_date' => null,
                'formation_hours' => 0,
            ]);
            $renewal->save();
            $renewal->update([
                'number_cfa' => str_pad((string) $renewal->id, 4, '0', STR_PAD_LEFT),
            ]);

            foreach ($source->trainingContractFestivals as $festival) {
                $renewal->trainingContractFestivals()->create($festival->only([
                    'nacional_festival_id',
                    'province_festival_id',
                    'population_festival_id',
                    'main_company_id',
                ]));
            }
            foreach ($source->trainingContractExcludedDays as $excludedDay) {
                $renewal->trainingContractExcludedDays()->create($excludedDay->only([
                    'excluded_day_type_id',
                    'day',
                    'description',
                    'group',
                    'main_company_id',
                ]));
            }

            return $renewal->fresh();
        });
    }
}

<?php

namespace App\Services;

use App\Models\Provider;
use App\Models\TrainingContract;
use App\Models\TrainingContractBonus;
use Carbon\Carbon;

class TrainingContractBonusService
{
    /**
     * Función para crear proveedor
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $trainingContract = TrainingContract::find($data['training_contract_id']);
        $bonus = TrainingContractBonus::create([
            'training_contract_id' => $trainingContract->id,
            'advisor_id' => $trainingContract->advisor_id,
            'collaborator_id' => $trainingContract->collaborator_id,
            'month' => $data['month'],
            'year' => $data['year'],
            'start' => Carbon::parse($data['start'])->toDateString(),
            'end' => Carbon::parse($data['end'])->toDateString(),
            'amount' => $data['amount'],
            'invoiced' => $data['invoiced'],
            'hours' => isset($data['hours']) ? $data['hours'] : null,
            'main_company_id' => $data['main_company_id']
        ]);
        return $bonus;
    }

    /**
     * Función para editar proveedor
     */
    public function update(TrainingContractBonus $trainingContractBonus, array $data) {
        $trainingContract = TrainingContract::find($data['training_contract_id']);

        $trainingContractBonus->update([
            'advisor_id' => $trainingContract->advisor_id,
            'collaborator_id' => $trainingContract->collaborator_id,
            'month' => $data['month'],
            'year' => $data['year'],
            'start' => Carbon::parse($data['start'])->toDateString(),
            'end' => Carbon::parse($data['end'])->toDateString(),
            'amount' => $data['amount'],
            'invoiced' => $data['invoiced'],
            'hours' => isset($data['hours']) ? $data['hours'] : null,
        ]);

        return $trainingContractBonus;
    }
}

<?php

namespace App\Services;

use App\Models\TrainingContract;
use App\Models\TrainingContractIncidence;
use Carbon\Carbon;

class TrainingContractIncidenceService
{
    /**
     * Función para crear proveedor
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $trainingContract_incidence = TrainingContractIncidence::create([
                'affair' => $data['affair'],
                'incidence_type_id' => $data['incidence_type_id'],
                'notes' => $data['notes'],
                'training_contract_id' => $data['training_contract_id'],
                'user_id' => $data['user_id'],
                'main_company_id' => $data['main_company_id'],
            ]
        );

        return $trainingContract_incidence;
    }

    /**
     * Función para editar proveedor
     */
    public function update(TrainingContractIncidence $trainingContractIncidence, array $data) {
        $trainingContractIncidence->update([
            'affair' => $data['affair'],
            'incidence_type_id' => $data['incidence_type_id'],
            'notes' => $data['notes'],
            'training_contract_id' => $data['training_contract_id'],
            'user_id' => $data['user_id']
        ]);

        return $trainingContractIncidence;
    }
}

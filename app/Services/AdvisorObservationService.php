<?php

namespace App\Services;

use App\Models\AdvisorObservation;

class AdvisorObservationService
{
    /**
     * Función para crear una observación
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return AdvisorObservation::create([
            'advisor_id' => $data['advisor_id'],
            'observation' => $data['observation'],
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar una observación
     */
    public function update(AdvisorObservation $advisorObservation, array $data) {
        $advisorObservation->update([
            'advisor_id' => $data['advisor_id'],
            'observation' => $data['observation']
        ]);
        return $advisorObservation;
    }
}

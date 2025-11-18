<?php

namespace App\Services;

use App\Models\CompanyObservation;

class CompanyObservationService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CompanyObservation::create([
            'company_id' => $data['company_id'],
            'observation' => $data['observation'],
            'main_company_id' => $data['main_company_id'],
        ]);
    }

    /**
     * Función para editar una comisión de una asesoría
     */
    public function update(CompanyObservation $companyObservation, array $data) {

        $companyObservation->update([
            'observation' => $data['observation']
        ]);

        return $companyObservation;
    }
}

<?php

namespace App\Services;

use App\Models\CompanyIncidence;

class CompanyIncidenceService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CompanyIncidence::create([
            'company_id' => $data['company_id'],
            'incidence_type_id' => $data['incidence_type_id'],
            'affair' => $data['affair'],
            'notes' => $data['notes'],
            'user_id' => $data['user_id'],
            'main_company_id' => $data['main_company_id']
        ]);
    }

    /**
     * Función para editar una comisión de una asesoría
     */
    public function update(CompanyIncidence $companyIncidence, array $data) {

        $companyIncidence->update([
            'company_id' => $data['company_id'],
            'incidence_type_id' => $data['incidence_type_id'],
            'affair' => $data['affair'],
            'notes' => $data['notes'],
            'user_id' => $data['user_id']
        ]);

        return $companyIncidence;
    }
}

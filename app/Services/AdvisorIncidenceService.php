<?php

namespace App\Services;

use App\Models\AdvisorIncidence;

class AdvisorIncidenceService
{
    /**
     * Función para crear una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return AdvisorIncidence::create([
            'advisor_id' => $data['advisor_id'],
            'incidence_type_id' => $data['incidence_type_id'],
            'affair' => $data['affair'],
            'notes' => $data['notes'],
            'user_id' => $data['user_id'],
            'main_company_id' => $data['main_company_id']
        ]);
    }

    /**
     * Función para editar una asesoría
     */
    public function update(AdvisorIncidence $advisor, array $data) {
        $advisor->update([
            'company_id' => $data['company_id'],
            'incidence_type_id' => $data['incidence_type_id'],
            'affair' => $data['affair'],
            'notes' => $data['notes'],
            'user_id' => $data['user_id']
        ]);
        return $advisor;
    }
}

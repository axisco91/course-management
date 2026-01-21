<?php

namespace App\Services;

use App\Models\CompanyActivity;

class CompanyActivityService
{
    /**
     * Función para crear una actividad
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CompanyActivity::create([
            'name' => $data['name'],
        ]);
    }

    /**
     * Función para editar una actividad
     */
    public function update(CompanyActivity $companyActivity, array $data) {
        $companyActivity->update([
            'name' => $data['name'],
        ]);
        return $companyActivity;
    }
}

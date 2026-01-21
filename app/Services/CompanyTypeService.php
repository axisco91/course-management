<?php

namespace App\Services;

use App\Models\CompanyType;

class CompanyTypeService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CompanyType::create([
            'name' => $data['name'],
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(CompanyType $companyType, array $data) {
        $companyType->update([
            'name' => $data['name'],
        ]);
        return $companyType;
    }
}

<?php

namespace App\Services;

use App\Models\ProfessionalCategory;

class ProfessionalCategoryService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return ProfessionalCategory::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(ProfessionalCategory $professionalCategory, array $data) {
        $professionalCategory->update([
            'name' => $data['name']
        ]);
        return $professionalCategory;
    }
}

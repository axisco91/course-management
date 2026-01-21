<?php

namespace App\Services;

use App\Models\ProfessionalFamily;

class ProfessionalFamilyService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return ProfessionalFamily::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(ProfessionalFamily $professionalFamily, array $data) {
        $professionalFamily->update([
            'name' => $data['name']
        ]);
        return $professionalFamily;
    }
}

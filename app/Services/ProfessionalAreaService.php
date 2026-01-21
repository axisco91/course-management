<?php

namespace App\Services;

use App\Models\ProfessionalArea;

class ProfessionalAreaService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return ProfessionalArea::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(ProfessionalArea $professionalArea, array $data) {
        $professionalArea->update([
            'name' => $data['name']
        ]);
        return $professionalArea;
    }
}

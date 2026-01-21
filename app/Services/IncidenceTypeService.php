<?php

namespace App\Services;

use App\Models\IncidenceType;

class IncidenceTypeService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return IncidenceType::create([
            'name' => $data['name'],
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(IncidenceType $incidenceType, array $data) {
        $incidenceType->update([
            'name' => $data['name'],
        ]);
        return $incidenceType;
    }
}

<?php

namespace App\Services;

use App\Models\Occupation;

class OccupationService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Occupation::create([
            'name' => $data['name'],
            'cno' => $data['cno']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(Occupation $occupation, array $data) {
        $occupation->update([
            'name' => $data['name'],
            'cno' => $data['cno']
        ]);
        return $occupation;
    }
}

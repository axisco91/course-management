<?php

namespace App\Services;

use App\Models\LevelStudy;

class LevelStudyService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return LevelStudy::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(LevelStudy $levelStudy, array $data) {
        $levelStudy->update([
            'name' => $data['name']
        ]);
        return $levelStudy;
    }
}

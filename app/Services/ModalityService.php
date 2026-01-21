<?php

namespace App\Services;

use App\Models\Modality;

class ModalityService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Modality::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(Modality $modality, array $data) {
        $modality->update([
            'name' => $data['name']
        ]);
        return $modality;
    }
}

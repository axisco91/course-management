<?php

namespace App\Services;

use App\Models\ActionType;

class ActionTypeService
{
    /**
     * Función para crear un tipo de acción
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return ActionType::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo de acción
     */
    public function update(ActionType $actionType, array $data) {
        $actionType->update([
            'name' => $data['name']
        ]);
        return $actionType;
    }
}

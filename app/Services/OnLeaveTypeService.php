<?php

namespace App\Services;

use App\Models\OnLeaveType;

class OnLeaveTypeService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return OnLeaveType::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(OnLeaveType $onLeaveType, array $data) {
        $onLeaveType->update([
            'name' => $data['name']
        ]);
        return $onLeaveType;
    }
}

<?php

namespace App\Services;

use App\Models\CommissionType;

class CommissionTypeService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CommissionType::create([
            'name' => $data['name'],
            'percentage' => $data['percentage']
        ]);
    }

    /**
     * Función para editar una comisión de una asesoría
     */
    public function update(CommissionType $commissionType, array $data) {
        $commissionType->update([
            'name' => $data['name'],
            'percentage' => $data['percentage']
        ]);
        return $commissionType;
    }
}

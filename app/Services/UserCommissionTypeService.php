<?php

namespace App\Services;

use App\Models\UserCommissionType;

class
UserCommissionTypeService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserCommissionType::create([
            'user_id' => $data['user_id'],
            'commission_type_id' => $data['commission_type_id'],
            'percentage' => $data['percentage'],
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar una comisión de una asesoría
     */
    public function update(UserCommissionType $userCommissionType, array $data) {
        $userCommissionType->update([
            'percentage' => $data['percentage']
        ]);
        return $userCommissionType;
    }

    public function delete(UserCommissionType $userCommissionType) {
        $userCommissionType->delete();
    }
}

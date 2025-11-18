<?php

namespace App\Services;

use App\Models\UserCommission;

class UserCommissionService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserCommission::create([
            'user_id' => $data['user_id'],
            'training_contract_id' => isset($data['training_contract_id']) ? $data['training_contract_id'] : null,
            'course_id' => isset($data['course_id']) ? $data['course_id'] : null,
            'commissionable_id' => $data['commissionable_id'],
            'commissionable_type' => $data['commissionable_type'],
            'commission_type_id' => $data['commission_type_id'],
            'percentage' => isset($data['percentage']) ? $data['percentage'] : null,
            'amount' => isset($data['amount']) ? $data['amount'] : null,
            'bill_amount' => isset($data['bill_amount']) ? $data['bill_amount'] : null,
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar una comisión de una asesoría
     */
    public function update(UserCommission $userCommission, array $data) {
        $userCommission->update([
            'commission_type_id' => $data['commission_type_id'],
            'percentage' => isset($data['percentage']) ? $data['percentage'] : null,
            'amount' => isset($data['amount']) ? $data['amount'] : null,
            'bill_amount' => isset($data['bill_amount']) ? $data['bill_amount'] : null,
        ]);
        return $userCommission;
    }
}

<?php

namespace App\Services;


use App\Models\AdvisorCommissionType;

class AdvisorCommissionTypeService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return AdvisorCommissionType::create([
            'advisor_id' => $data['advisor_id'],
            'commission_type_id' => $data['commission_type_id'],
            'percentage' => $data['percentage']
        ]);
    }

    /**
     * Función para editar una comisión de una asesoría
     */
    public function update(AdvisorCommissionType $advisorCommissionType, array $data) {
        $advisorCommissionType->update([
            'percentage' => $data['percentage']
        ]);
        return $advisorCommissionType;
    }

    public function delete(AdvisorCommissionType $advisorCommissionType) {
        $advisorCommissionType->delete();
    }
}

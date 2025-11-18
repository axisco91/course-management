<?php

namespace App\Services;

use App\Models\Credit;

class CreditService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Credit::create([
            'company_id' => $data['company_id'],
            'available_credit' => $data['available_credit'],
            'consumed_credit' => $data['consumed_credit'],
            'year' => $data['year'],
            'main_company_id' => $data['main_company_id'],
        ]);
    }

    /**
     * Función para editar una comisión de una asesoría
     */
    public function update(Credit $credit, array $data) {

        $credit->update([
            'company_id' => $data['company_id'],
            'available_credit' => $data['available_credit'],
            'consumed_credit' => $data['consumed_credit'],
            'year' => $data['year']
        ]);

        return $credit;
    }
}

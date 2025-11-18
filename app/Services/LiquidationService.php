<?php

namespace App\Services;

use App\Models\Liquidation;
use Carbon\Carbon;

class LiquidationService
{
    /**
     * Función para crear un centro
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Liquidation::create([
            'company_id' => $data['company_id'],
            'course_id' => $data['course_id'],
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'price' => $data['price'],
            'paid' => $data['paid'],
            'commission_percent' => $data['commission_percent'],
            'commission' => $data['commission'],
            'paid_date' => $data['paid_date'] ?? Carbon::parse($data['paid_date'])->format('Y-m-d'),
            'invoice_date' => $data['invoice_date'] ?? Carbon::parse($data['invoice_date'])->format('Y-m-d'),
            'advisor_id' => $data['advisor_id'] ?? null,
            'collaborator_id' => $data['collaborator_id'] ?? null,
            'bill_number' => $data['bill_number'],
            'status' => $data['status'] ?? 1,
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar una liquidación
     */
    public function update(Liquidation $liquidation, array $data) {
        $liquidation->update([
            'company_id' => $data['company_id'],
            'course_id' => $data['course_id'],
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'price' => $data['price'],
            'paid' => $data['paid'],
            'commission_percent' => $data['commission_percent'],
            'commission' => $data['commission'],
            'paid_date' => $data['paid_date'] ? Carbon::parse($data['paid_date'])->format('Y-m-d') : '',
            'invoice_date' => $data['invoice_date'] ? Carbon::parse($data['invoice_date'])->format('Y-m-d') : '',
            'advisor_id' => $data['advisor_id'],
            'bill_number' => $data['bill_number'],
            'status' => $data['status'] ?? 1,
        ]);
        return $liquidation;
    }

    /**
     * Función para editar una liquidación
     */
    public function updateCommission(Liquidation $liquidation, array $data) {

        return $liquidation;
    }
}

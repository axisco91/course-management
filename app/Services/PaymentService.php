<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    /**
     * Función para crear un tipo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Payment::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un tipo
     */
    public function update(Payment $payment, array $data) {
        $payment->update([
            'name' => $data['name']
        ]);
        return $payment;
    }
}

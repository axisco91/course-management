<?php

namespace App\Services;

use App\Models\Center;

class CenterService
{
    /**
     * Función para crear un centro
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Center::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar un centro
     */
    public function update(Center $center, array $data) {
        $center->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'telephone' => $data['telephone']
        ]);
        return $center;
    }
}

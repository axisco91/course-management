<?php

namespace App\Services;

use App\Models\Province;

class ProvinceService
{
    /**
     * Función para crear una provincia
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Province::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar una provincia
     */
    public function update(Province $province, array $data) {
        $province->update([
            'name' => $data['name']
        ]);
        return $province;
    }
}

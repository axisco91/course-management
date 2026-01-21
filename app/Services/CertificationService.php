<?php

namespace App\Services;

use App\Models\Certification;

class CertificationService
{
    /**
     * Función para crear una observación
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Certification::create([
            'code'                   => $data['code'],
            'name'                   => $data['name'],
            'professional_family_id' => $data['professional_family_id'],
            'professional_area_id'   => $data['professional_area_id'],
            'level'                  => $data['level'],
            'active'                 => $data['active'],
            'main_company_id'        => $data['main_company_id'] ?? null,
        ]);
    }

    /**
     * Función para editar una observación
     */
    public function update(Certification $certification, array $data) {
        $certification->update([
            'code'                   => $data['code'],
            'name'                   => $data['name'],
            'professional_family_id' => $data['professional_family_id'],
            'professional_area_id'   => $data['professional_area_id'],
            'level'                  => $data['level'],
            'active'                 => $data['active'],
        ]);
    }
}

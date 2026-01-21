<?php

namespace App\Services;

use App\Models\QuoteGroup;

class QuoteGroupService
{
    /**
     * Función para crear un grupo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return QuoteGroup::create([
            'name' => $data['name']
        ]);
    }

    /**
     * Función para editar un grupo
     */
    public function update(QuoteGroup $quoteGroup, array $data) {
        $quoteGroup->update([
            'name' => $data['name']
        ]);
        return $quoteGroup;
    }
}

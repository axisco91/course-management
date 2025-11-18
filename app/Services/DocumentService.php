<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Str;

class DocumentService
{
    /**
     * Función para crear un documento
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $currentDate = now()->format('Ymd');
        $randomString = Str::random(8);
        $generatedKey = $currentDate . $randomString;

        return Document::create([
            'document_type_id' => $data['document_type_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'blade' => isset($data['blade']) ? $data['blade'] : '',
            'route' => isset($data['route']) ? $data['route'] : null,
            'key' => $generatedKey,
            'signature' => isset($data['signature']) ? $data['signature'] : 0,
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar un documento
     */
    public function update(Document $document, array $data) {
        $document->update([
            'document_type_id' => $data['document_type_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'blade' => isset($data['blade']) ? $data['blade'] : '',
            'route' => isset($data['route']) ? $data['route'] : null,
            'signature' => isset($data['signature']) ? $data['signature'] : 0,
        ]);
        return $document;
    }
}

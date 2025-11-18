<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentStudent;
use Carbon\Carbon;

class DocumentStudentService
{
    /**
     * Función para crear un documento de alumno
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $key = Carbon::now()->timestamp;
        $key = substr($key, -10);
        return DocumentStudent::create([
            'document_id' => $data['document_id'],
            'name' => $data['name'],
            'student_id' => $data['student_id'],
            'training_contract_id' => $data['training_contract_id'],
            'document_name' => $data['document_name'],
            'key' => $key,
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar un documento de alumno
     */
    public function update(DocumentStudent $documentStudent, array $data) {
        $documentStudent->update([
            'name' => $data['name'],
            'document_name' => $data['document_name'],
            'signed' => $data['signed'],
            'date_signed' => $data['date_signed']
        ]);
        return $documentStudent;
    }
}

<?php

namespace App\Models;

use App\Services\DocumentStudentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStudent extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'document_id', 'training_contract_id', 'name', 'document_name', 'key', 'signed', 'date_signed', 'main_company_id'];

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('document_students.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(DocumentStudentService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(DocumentStudentService::class);
        return $service->update($this, $data);
    }
}

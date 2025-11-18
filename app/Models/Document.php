<?php

namespace App\Models;

use App\Services\DocumentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'document_type_id', 'description', 'blade', 'route', 'coordinates', 'key', 'signature', 'main_company_id'];

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('documents.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(DocumentService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(DocumentService::class);
        return $service->update($this, $data);
    }
}

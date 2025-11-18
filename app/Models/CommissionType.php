<?php

namespace App\Models;

use App\Services\CommissionTypeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'percentage',
        'main_company_id'
    ];

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('commission_types.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(CommissionTypeService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CommissionTypeService::class);
        return $service->update($this, $data);
    }

}

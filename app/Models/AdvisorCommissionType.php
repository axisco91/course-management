<?php

namespace App\Models;

use App\Services\AdvisorCommissionTypeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorCommissionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'advisor_id',
        'commission_type_id',
        'percentage',
        'main_company_id'
    ];

    public static function createWithService($data)
    {
        $service = app(AdvisorCommissionTypeService::class);
        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(AdvisorCommissionTypeService::class);
        return $service->update($this, $data);
    }

    public function deleteWithService()
    {
        $service = app(AdvisorCommissionTypeService::class);
        return $service->delete($this);
    }
}

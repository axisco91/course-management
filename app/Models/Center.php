<?php

namespace App\Models;

use App\Services\CenterService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','address','email','telephone', 'main_company_id'];

    public function scopeGetCenter($query, $mainCompanyId) {
        return $query->select('centers.*', 'id as value', 'name as label')
            ->where('main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('centers.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(CenterService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CenterService::class);
        return $service->update($this, $data);
    }
}

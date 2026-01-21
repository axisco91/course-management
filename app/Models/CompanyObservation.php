<?php

namespace App\Models;

use App\Services\CompanyObservationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyObservation extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['company_id','observation', 'main_company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('company_observations.main_company_id', $mainCompanyId);
    }

    public function scopeGetCompanyObservations($query, $id, $mainCompanyId)
    {
        return $query
            ->select(
                'company_observations.*',
                DB::raw("DATE_FORMAT(company_observations.created_at, '%d/%m/%Y') as date")
            )
            ->where('company_observations.company_id', $id)
            ->where('company_observations.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(CompanyObservationService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CompanyObservationService::class);
        return $service->update($this, $data);
    }
}

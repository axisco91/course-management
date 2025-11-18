<?php

namespace App\Models;

use App\Services\CompanyObservationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getCompanyObservations($id, $mainCompanyId){
        $observations = CompanyObservation::where('company_id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->get();

        foreach ($observations as $observation){
            $observation['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $observation['created_at'])->format('d/m/Y');
        }

        return $observations;
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

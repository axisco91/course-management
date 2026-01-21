<?php

namespace App\Models;

use App\Services\CompanyIncidenceService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyIncidence extends Model
{
	use HasFactory;

    protected $fillable = [
        'affair',
        'notes',
        'incidence_type_id',
        'company_id',
        'user_id',
        'main_company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ Tipo de incidencia
    public function incidenceType()
    {
        return $this->belongsTo(IncidenceType::class, 'incidence_type_id');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('company_incidences.main_company_id', $mainCompanyId);
    }

    public function scopeFilterCompany($query, $companyId)
    {
        return $query->where('company_incidences.company_id', $companyId);
    }

    public function scopeGetCompanyIncidences($query, $companyId, $mainCompanyId)
    {
        return $query
            ->with([
                'user:id,name,surname',
                'incidenceType:id,name'
            ])
            ->select('company_incidences.*')
            ->filterCompany($companyId)
            ->filterMainCompany($mainCompanyId);

    }

    // Antes: static getCompanyIncidence($id, $mainCompanyId)
    public function scopeGetCompanyIncidence($query, $id, $mainCompanyId)
    {
        return $query
            ->select(
                'company_incidences.*',
                'incidence_types.name as incidence_type',
                DB::raw("CONCAT(users.name,' ',users.surname) as user")
            )
            ->leftJoin('incidence_types', 'incidence_types.id', '=', 'company_incidences.incidence_type_id')
            ->leftJoin('users', 'users.id', '=', 'company_incidences.user_id')
            ->where('company_incidences.id', $id)
            ->filterMainCompany($mainCompanyId);
        // Aquí tampoco hacemos ->first()
    }

    public static function createWithService($data)
    {
        $service = app(CompanyIncidenceService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CompanyIncidenceService::class);
        return $service->update($this, $data);
    }
}

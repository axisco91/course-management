<?php

namespace App\Models;

use App\Services\CompanyIncidenceService;
use Carbon\Carbon;
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

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('company_incidences.main_company_id', $mainCompanyId);
    }

    public static function getCompanyIncidences($companyId, $mainCompanyId){
        $companyIncidences = CompanyIncidence::select('company_incidences.*',
            'company_incidences.id as value',
            'company_incidences.affair as label',
            'incidence_types.name as incidence_type',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'company_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'company_incidences.user_id')
            ->where('company_id', $companyId)
            ->where('company_incidences.main_company_id', $mainCompanyId)->get();
        foreach ($companyIncidences as $companyIncidence) {
            $companyIncidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $companyIncidence['created_at'])->format('d/m/Y');
        }
        return $companyIncidences;
    }

    public static function getCompanyIncidence($id, $mainCompanyId){
        $companyIncidence = CompanyIncidence::select('company_incidences.*', 'incidence_types.name as incidence_type',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'company_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'company_incidences.user_id')
            ->where('company_incidences.id', $id)
            ->where('company_incidences.main_company_id', $mainCompanyId)
            ->first();
        $companyIncidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $companyIncidence['created_at'])->format('d/m/Y');
        return $companyIncidence;
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

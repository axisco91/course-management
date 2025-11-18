<?php

namespace App\Models;

use App\Services\PotentialCompanyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialCompany extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name',
        'nif',
        'company_type_id',
        'company_activity_id',
        'email',
        'telephone',
        'legal_representative',
        'dni_legal_representative',
        'quote',
        'cnae_id',
        'average_template',
        'iban',
        'sepa',
        'b2b',
        'address',
        'post_code',
        'population_id',
        'province_id',
        'population',
        'advisor_name',
        'converted',
        'main_company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cnae()
    {
        return $this->hasOne('App\Models\Cnae', 'id', 'cnae_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function companyActivity()
    {
        return $this->hasOne('App\Models\CompanyActivity', 'id', 'activity_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function companyType()
    {
        return $this->hasOne('App\Models\CompanyType', 'id', 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function population()
    {
        return $this->hasOne('App\Models\Population', 'id', 'population_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function province()
    {
        return $this->hasOne('App\Models\Province', 'id', 'province_id');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('potential_companies.main_company_id', $mainCompanyId);
    }


    public static function getPotentialCompanies($mainCompanyId){
        return PotentialCompany::select('potential_companies.*', 'company_types.name as type',
            'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province')
            ->leftjoin('company_types', 'company_types.id', '=', 'potential_companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'potential_companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'potential_companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_companies.province_id')->orderBy('potential_companies.name', 'asc')
            ->where('potential_companies.main_company_id', $mainCompanyId)
            ->get();
    }

    public static function findNif($nif, $mainCompanyId, $id = null){
        $company = Company::where('nif', $nif)
            ->where('main_company_id', $mainCompanyId);
        if ($id){
            $company = $company->where('id', '!=', $id);
        }
        $company = $company->first();

        return $company;
    }

    public static function createWithService($data)
    {
        $service = app(PotentialCompanyService::class);
        return $service->create($data);
    }

    public function convertPotentialCompany(){
        $service = app(PotentialCompanyService::class);
        return $service->converted($this);
    }
}

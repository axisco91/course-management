<?php

namespace App\Models;

use App\Services\AdvisorService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Advisor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name','company_id','irpf','commission','contact_1','contact_2','contact_3',
        'nif','company_type_id','company_activity_id','email','telephone',
        'legal_representative','dni_legal_representative','cnae_id','iban',
        'sepa','b2b','address','post_code','population_id','province_id',
        'population','active','collaborator_id','user_id','main_company_id'
    ];

    /**
     * Relaciones
     */
    public function companyType()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id');
    }

    public function companyActivity()
    {
        return $this->belongsTo(CompanyActivity::class, 'company_activity_id');
    }

    public function cnae()
    {
        return $this->belongsTo(Cnae::class, 'cnae_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'advisor_id', 'id');
    }

    public function commissions()
    {
        return $this->hasMany(AdvisorCommission::class);
    }

    /**
     * Scopes
     */
    public function scopeGetAdvisor($query, $mainCompanyId)
    {
        return $query
            ->select('advisors.*')
            ->with([
                'companyType:id,name',
                'companyActivity:id,name',
                'cnae:id,name',
                'province:id,name',
                'company:id,quote,average_template',       // lo que necesitas
                'collaborator:id,name,surname',
            ])
            ->FilterMainCompany($mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId)
    {
        return $query->where('advisors.main_company_id', $mainCompanyId);
    }

    /**
     * Scope equivalente a findNif (con opción de excluir un id y filtrar por main_company)
     *
     * Uso:
     *  Advisor::findNif($nif)->first();
     *  Advisor::findNif($nif, $excludeId)->first();
     *  Advisor::findNif($nif, null, $mainCompanyId)->first();
     */
    public function scopeFindNif($query, string $nif, int $mainCompanyId, ?int $excludeId = null)
    {
        $query->where('nif', $nif)
            ->FilterMainCompany($mainCompanyId);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }

    /**
     * Services
     */
    public static function createWithService($data)
    {
        $service = app(AdvisorService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(AdvisorService::class);

        return $service->update($this, $data);
    }

    public function updateAdvisorCompany($data)
    {
        $service = app(AdvisorService::class);

        return $service->updateAdvisorCompany($this, $data);
    }

    public static function convertAdvisor($id)
    {
        $service = app(AdvisorService::class);

        return $service->convertAdvisor($id);
    }

    public function advisorUser()
    {
        $service = app(AdvisorService::class);

        return $service->advisorUser($this);
    }

    public function sendEmail()
    {
        $service = app(AdvisorService::class);

        return $service->sendEmail($this);
    }
}

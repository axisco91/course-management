<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Advisor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','company_id','irpf','commission','contact_1','contact_2','contact_3', 'nif', 'company_type_id', 'company_activity_id', 'email', 'telephone', 'legal_representative', 'dni_legal_representative', 'cnae_id', 'iban', 'sepa', 'b2b', 'address', 'post_code', 'population_id', 'province_id', 'population', 'active', 'collaborator_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'advisor_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commissions()
    {
        return $this->hasMany(AdvisorCommission::class);
    }

    public function scopeGetAdvisor($query) {
        return $query->select('advisors.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'advisors.id as value', 'advisors.name as label', 'companies.quote as quote', 'companies.average_template as average_template', 'users.id as collaborator_id',
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"))
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id')
            ->leftjoin('companies', 'companies.id', '=', 'advisors.company_id')
            ->leftjoin('users', 'users.id', '=', 'advisors.collaborator_id');
    }

    public static function findNif($nif, $id = null){
        $advisor = Advisor::where('nif', $nif);
        if ($id){
            $advisor = $advisor->where('id', '!=', $id);
        }
        $advisor = $advisor->first();
        return $advisor;
    }
}

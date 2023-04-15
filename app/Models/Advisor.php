<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getAdvisors(){
        $advisors = Advisor::select('advisors.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'advisors.id as value', 'advisors.name as label')
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id')->orderBy('advisors.name', 'desc')
            ->get();
        foreach ($advisors as $advisor) {
            $company = Company::where('advisor_id', $advisor->id)->first();
            if ($company) {
                $advisor['used'] = true;
            } else {
                $advisor['used'] = false;
            }
        }
        return $advisors;
    }

    public static function getAdvisor($id){
        $advisor = Advisor::select('advisors.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'advisors.id as value', 'advisors.name as label')
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id')->orderBy('advisors.name', 'desc')
            ->where('advisors.id', $id)
            ->first();
        $company = Company::where('advisor_id', $advisor->id)->first();
        if ($company) {
            $advisor['used'] = true;
        } else {
            $advisor['used'] = false;
        }
        return $advisor;
    }

    public static function createAdvisor($data){
        $advisor = Advisor::create([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'collaborator_id' => $data['collaborator_id'],
            'active' => $data['active']
        ]);

        return $advisor;
    }

    public static function updateAdvisor($id, $data){
        $advisor = Advisor::find($id);
        $advisor->update([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'collaborator_id' => $data['collaborator_id'],
            'active' => $data['active']
        ]);

        return $advisor;
    }

    public static function updateAdvisorCompany($id, $data){
        $advisor = Advisor::find($id);
        $advisor->update([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active'],
        ]);
        return $advisor;
    }

    public static function convertAdvisor($id){
        if ($id) {
            $record = Company::find($id);
            $advisor = Advisor::create([
                'name' => $record['name'],
                'company_id' => $id,
                'nif' => $record['nif'],
                'company_type_id' => $record['company_type_id'],
                'company_activity_id' => $record['company_activity_id'],
                'email' => $record['email'],
                'telephone' => $record['telephone'],
                'legal_representative' => $record['legal_representative'],
                'dni_legal_representative' => $record['dni_legal_representative'],
                'cnae_id' => $record['cnae_id'],
                'iban' => $record['iban'],
                'sepa' => $record['sepa'],
                'b2b' => $record['b2b'],
                'address' => $record['address'],
                'post_code' => $record['post_code'],
                'province_id' => $record['province_id'],
                'population' => $record['population'],
                'active' => $record['active'],
            ]);
            return $advisor;
        }
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

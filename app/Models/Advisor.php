<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','company_id','irpf','commission','contact_1','contact_2','contact_3'];

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

    public function getAdvisors($keyWord, $inactiveFilter){
        $advisors = Company::select('advisors.id as advisor_id','advisors.irpf', 'advisors.commission',
            'advisors.contact_1', 'advisors.contact_2', 'advisors.contact_3', 'companies.*',
            'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province',
            'advisors.name as advisor')
            ->join('advisors', 'advisors.company_id', '=', 'companies.id')
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('advisors as a', 'advisors.id', '=', 'companies.advisor_id');


        if ($inactiveFilter != 1) {
            $advisors = $advisors->where('inactive', 0);
        }

        $advisors = $advisors->where(function ($query) use ($keyWord){
            $query->orWhere('advisors.name', 'LIKE', $keyWord)
                ->orWhere('advisors.irpf', 'LIKE', $keyWord)
                ->orWhere('advisors.commission', 'LIKE', $keyWord)
                ->orWhere('advisors.contact_1', 'LIKE', $keyWord)
                ->orWhere('advisors.contact_2', 'LIKE', $keyWord)
                ->orWhere('advisors.contact_3', 'LIKE', $keyWord)
                ->orWhere('nif', 'LIKE', $keyWord)
                ->orWhere('company_types.name', 'LIKE', $keyWord)
                ->orWhere('company_activities.name', 'LIKE', $keyWord)
                ->orWhere('email', 'LIKE', $keyWord)
                ->orWhere('telephone', 'LIKE', $keyWord)
                ->orWhere('legal_representative', 'LIKE', $keyWord)
                ->orWhere('dni_legal_representative', 'LIKE', $keyWord)
                ->orWhere('quote', 'LIKE', $keyWord)
                ->orWhere('cnaes.name', 'LIKE', $keyWord)
                ->orWhere('average_template', 'LIKE', $keyWord)
                ->orWhere('iban', 'LIKE', $keyWord)
                ->orWhere('sepa', 'LIKE', $keyWord)
                ->orWhere('b2b', 'LIKE', $keyWord)
                ->orWhere('address', 'LIKE', $keyWord)
                ->orWhere('post_code', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('population', 'LIKE', $keyWord)
                ->orWhere('active', 'LIKE', $keyWord)
                ->orWhere('advisors.name', 'LIKE', $keyWord);
        })->orderBy('name', 'desc')
            ->paginate(10);

        return $advisors;
    }

    public function createAdvisor($data){
        $advisor = Advisor::create([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3']
        ]);

        return $advisor;
    }

    public function updateAdvisor($id, $data){
        $advisor = Advisor::find($id);
        $advisor->update([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3']
        ]);

        return $advisor;
    }

    public function convertAdvisor($id){
        if ($id) {
            $record = Company::find($id);
            $advisor = Advisor::create([
                'name' => $record['name'],
                'company_id' => $record['id']
            ]);
           return $advisor;
        }
    }

}

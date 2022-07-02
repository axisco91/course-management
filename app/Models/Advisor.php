<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','company_id','irpf','commission','contact_1','contact_2','contact_3', 'nif', 'company_type_id', 'company_activity_id', 'email', 'telephone', 'legal_representative', 'dni_legal_representative', 'cnae_id', 'iban', 'sepa', 'b2b', 'address', 'post_code', 'population_id', 'province_id', 'population', 'active'];

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

    public function getAdvisors($keyWord, $inactiveFilter, $search_name, $search_nif, $search_type_id, $search_activity_id, $search_province_id){
        $advisors = Advisor::select('advisors.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province')
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id');


        if ($inactiveFilter != 1) {
            $advisors = $advisors->where('active', 1);
        }

        $advisors = $advisors->where(function ($query) use ($keyWord){
            $query->orWhere('advisors.name', 'LIKE', $keyWord)
                ->orWhere('advisors.irpf', 'LIKE', $keyWord)
                ->orWhere('advisors.commission', 'LIKE', $keyWord)
                ->orWhere('advisors.contact_1', 'LIKE', $keyWord)
                ->orWhere('advisors.contact_2', 'LIKE', $keyWord)
                ->orWhere('advisors.contact_3', 'LIKE', $keyWord)
                ->orWhere('advisors.nif', 'LIKE', $keyWord)
                ->orWhere('company_types.name', 'LIKE', $keyWord)
                ->orWhere('company_activities.name', 'LIKE', $keyWord)
                ->orWhere('advisors.email', 'LIKE', $keyWord)
                ->orWhere('advisors.telephone', 'LIKE', $keyWord)
                ->orWhere('advisors.legal_representative', 'LIKE', $keyWord)
                ->orWhere('advisors.dni_legal_representative', 'LIKE', $keyWord)
                ->orWhere('cnaes.name', 'LIKE', $keyWord)
                ->orWhere('advisors.address', 'LIKE', $keyWord)
                ->orWhere('advisors.post_code', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('advisors.population', 'LIKE', $keyWord)
                ->orWhere('advisors.active', 'LIKE', $keyWord);
        })->where(function ($query) use ($search_name){
            $query->orWhere('advisors.name', 'LIKE', $search_name);
        })->where(function ($query) use ($search_nif){
            $query->orWhere('advisors.nif', 'LIKE', $search_nif);
        });
        if ($search_type_id){
            $advisors = $advisors->where(function ($query) use ($search_type_id){
                $query->orWhere('advisors.company_type_id', $search_type_id);
            });
        }
        if ($search_activity_id){
            $advisors = $advisors->where(function ($query) use ($search_activity_id){
                $query->orWhere('advisors.company_activity_id', $search_activity_id);
            });
        }
       if ($search_province_id){
           $advisors = $advisors->where(function ($query) use ($search_province_id){
               $query->orWhere('advisors.province_id', $search_province_id);
           });
       }
        $advisors = $advisors->orderBy('advisors.name', 'desc')
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
            'active' => $data['active'],
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
            'active' => $data['active'],
        ]);

        return $advisor;
    }

    public function updateAdvisorCompany($id, $data){
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

    public function findNif($nif, $id = null){
        $advisor = Advisor::where('nif', $nif);
        if ($id){
            $advisor = $advisor->where('id', '!=', $id);
        }
        $advisor = $advisor->first();

        return $advisor;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','nif','company_type_id','company_activity_id','email','telephone','legal_representative','dni_legal_representative','quote','cnae_id','average_template','iban','sepa','b2b','address','post_code','population_id','province_id','population','active','advisor_id', 'active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function advisors()
    {
        return $this->hasMany('App\Models\Advisor', 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function advisor()
    {
        return $this->hasOne('App\Models\Advisor', 'id', 'advisor_id');
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyObservations()
    {
        return $this->hasMany('App\Models\CompanyObservation', 'company_id', 'id');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'company_id', 'id');
    }

    public function getCompany($keyWord, $search_name, $search_nif){
        $companies = Company::select('companies.*', 'company_types.name as type',
            'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province',
            'advisors.name as advisor')
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id');
        if ($this->inactiveFilter != 1) {
            $companies = $companies->where('active', 1);
        }
        $companies = $companies->where(function ($query) use ($keyWord){
            $query->orWhere('companies.name', 'LIKE', $keyWord)
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
                ->orWhere('advisors.name', 'LIKE', $keyWord);
        })->where(function ($query) use ($search_name){
            $query->orWhere('companies.name', 'LIKE', $search_name);
        })->where(function ($query) use ($search_nif){
            $query->orWhere('companies.nif', 'LIKE', $search_nif);
        })->orderBy('companies.name', 'desc')
            ->paginate(10);

        foreach ($companies as $company) {
            $advisor = Advisor::where('company_id', $company['id'])->first();
            if (!$advisor) {
                $company['is_advisor'] = true;
            }
            $provider = Provider::where('company_id', $company['id'])->first();
            if (!$provider) {
                $company['is_provider'] = true;
            }
        }

        return $companies;
    }

    public function createCompany($data){
        $company = Company::create([
            'name' => $data['name'],
            'nif' => $data['nif'],
            'company_type_id' => $data['type_id'],
            'company_activity_id' => $data['activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'quote' => $data['quote'],
            'cnae_id' => $data['cnae_id'],
            'average_template' => $data['average_template'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active'],
            'advisor_id' => $data['advisor_id']
        ]);

        return $company;
    }

    public function updateCompany($id, $data){
        $company = Company::find($id);
        $company->update([
            'name' => $data['name'],
            'nif' => $data['nif'],
            'company_type_id' => $data['type_id'],
            'company_activity_id' => $data['activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'quote' => $data['quote'],
            'cnae_id' => $data['cnae_id'],
            'average_template' => $data['average_template'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active'],
            'advisor_id' => $data['advisor_id']
        ]);
    }

    public function changeState($id){
        $companies = Company::find($id);
        if ($companies->active == 1){
            $companies->update([
                'active' => 0
            ]);
        } else {
            $companies->update([
                'active' => 1
            ]);
        }

        return $companies->active;
    }

}

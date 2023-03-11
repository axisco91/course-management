<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
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
        'advisor_id',
        'active',
        'collaborator_id',
        'potential'];

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

    public static function getCompanies(){
        $companies = Company::select('companies.*', 'company_types.name as type',
            'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province',
            'advisors.name as advisor',
            'users.name as user_name',
            'users.surname as user_surname',
            'companies.id as value',
            'companies.name as label',
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"),
            DB::raw("'real' as type_company"))
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'companies.collaborator_id');
        $companies = $companies->orderBy('companies.name', 'asc')
            ->get();

        foreach ($companies as $company) {
            $student = Student::where('company_id', $company['id'])->first();
            if ($student) {
                $company['used'] = true;
            } else {
                $advisor = Advisor::where('company_id', $company['id'])->first();
                if ($advisor){
                    $company['used'] = true;
                } else {
                    $provider = Provider::where('company_id', $company['id'])->first();
                    if ($provider) {
                        $company['used'] = true;
                    } else {
                        $company['used'] = false;
                    }
                }
            }
            $advisor = Advisor::where('company_id', $company['id'])->first();
            if ($advisor) {
                $company['is_advisor'] = true;
            } else {
                $company['is_advisor'] = false;
            }
            $provider = Provider::where('company_id', $company['id'])->first();
            if ($provider) {
                $company['is_provider'] = true;
            } else {
                $company['is_provider'] = false;
            }
            if ($company['potential'] === 1) {
                $company['status'] = 'Potencial';
            } else if ($company['active'] === 0) {
                $company['status'] = 'Inactivo';
            } else {
                $company['status'] = 'Activo';
            }
        }

        return $companies;
    }

    public static function createCompany($data){
        $company = Company::create([
            'name' => $data['name'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
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
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
        ]);

        if ($data['active'] !== '') {
            $company->update([
                'active' => $data['active'] == true ? 1 : 0,
            ]);
        }
        if ($data['potential'] !== '') {
            $company->update([
                'potential' => $data['potential'] == true ? 1 : 0,
            ]);
        }

        return $company;
    }

    public static function updateCompany($id, $data){
        $company = Company::find($id);
        $company->update([
            'name' => $data['name'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
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
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id']
        ]);

        if ($data['active'] !== '') {
            $company->update([
                'active' => $data['active'] == true ? 1 : 0,
            ]);
        }
        if ($data['potential'] !== '') {
            $company->update([
                'potential' => $data['potential'] == true ? 1 : 0,
            ]);
        }
        return $company;
    }

    public static function changeState($id){
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

    public static function getAdvisorsCompanies($id, $search_company_name){
        $companies = Company::where('advisor_id', $id)
            ->where(function ($query) use ($search_company_name) {
                $query->orWhere('name', 'LIKE', $search_company_name);
            })->get();

        return $companies;
    }

    public static function findNif($nif, $id = null){
        $company = Company::where('nif', $nif);
        if ($id){
            $company = $company->where('id', '!=', $id);
        }
        $company = $company->first();
        if (!$company){
            $company = PotentialCompany::where('nif', $nif);
            if ($id){
                $company = $company->where('id', '!=', $id);
            }
            $company = $company->first();
        }
        return $company;
    }

    public static function convertCompany($potential_id, $data){
        $company = Company::createCompany($data);
        $potential_observations = PotentialCompanyObservation::where('potential_company_id', $potential_id)->get();
        foreach ($potential_observations as $potential_observation){
            $data = [
                'company_id' => $company->id,
                'observation' => $potential_observation->observation
            ];
            CompanyObservation::createCompanyObservation($data);
        }
        PotentialCompany::find($potential_id)->delete();
    }

}

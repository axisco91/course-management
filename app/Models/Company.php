<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','nif','company_type_id','company_activity_id','email','telephone','legal_representative','dni_legal_representative','quote','cnae_id','average_template','iban','sepa','b2b','address','post_code','population_id','province_id','population','advisor_id', 'active', 'collaborator_id', 'potential'];

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

    public static function getCompanies($keyWord, $search_name, $search_nif, $search_type_id, $search_activity_id, $search_advisor_id, $search_province_id, $status, $collaborator_id){
        $companies = Company::select('companies.*', 'company_types.name as type',
            'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province',
            'advisors.name as advisor',
            'users.name as user_name',
            'users.surname as user_surname',
            DB::raw("'real' as type_company"))
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'companies.collaborator_id');
        if ($status == 3) {
            $companies = $companies->where('companies.potential', 1);
        }else if ($status == 2){
            $companies = $companies->where('companies.active', 0)->where('companies.potential', 0);
        } else if ($status == 1){
            $companies = $companies->where('companies.active', 1)->where('companies.potential', 0);
        }
        $companies = $companies->where(function ($query) use ($keyWord){
            $query->orWhere('companies.name', 'LIKE', $keyWord)
                ->orWhere('companies.nif', 'LIKE', $keyWord)
                ->orWhere('company_types.name', 'LIKE', $keyWord)
                ->orWhere('company_activities.name', 'LIKE', $keyWord)
                ->orWhere('companies.email', 'LIKE', $keyWord)
                ->orWhere('companies.telephone', 'LIKE', $keyWord)
                ->orWhere('companies.legal_representative', 'LIKE', $keyWord)
                ->orWhere('companies.dni_legal_representative', 'LIKE', $keyWord)
                ->orWhere('quote', 'LIKE', $keyWord)
                ->orWhere('cnaes.name', 'LIKE', $keyWord)
                ->orWhere('average_template', 'LIKE', $keyWord)
                ->orWhere('companies.iban', 'LIKE', $keyWord)
                ->orWhere('companies.sepa', 'LIKE', $keyWord)
                ->orWhere('companies.b2b', 'LIKE', $keyWord)
                ->orWhere('companies.address', 'LIKE', $keyWord)
                ->orWhere('companies.post_code', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('companies.population', 'LIKE', $keyWord)
                ->orWhere('advisors.name', 'LIKE', $keyWord);
        });
        if ($search_name){
            $search_name = '%'.$search_name.'%';
            $companies = $companies->where(function ($query) use ($search_name){
                $query->orWhere('companies.name', 'LIKE', $search_name);
            });
        }
        if ($search_nif){
            $search_nif = '%'.$search_nif.'%';
            $companies = $companies->where(function ($query) use ($search_nif){
                $query->orWhere('companies.nif', 'LIKE', $search_nif);
            });
        }
        if ($search_type_id){
            $companies = $companies->where(function ($query) use ($search_type_id){
                $query->orWhere('companies.company_type_id', $search_type_id);
            });
        }
       if ($search_activity_id){
           $companies = $companies->where(function ($query) use ($search_activity_id){
               $query->orWhere('companies.company_activity_id', $search_activity_id);
           });
       }
       if ($search_advisor_id){
           $companies = $companies->where(function ($query) use ($search_advisor_id){
               $query->orWhere('companies.advisor_id', $search_advisor_id);
           });
       }
       if ($search_province_id){
           $companies = $companies->where(function ($query) use ($search_province_id){
               $query->orWhere('companies.province_id', $search_province_id);
           });
       }
       if ($collaborator_id){
           $companies = $companies->where(function ($query) use ($collaborator_id){
               $query->orWhere('companies.collaborator_id', $collaborator_id);
           });
       }

        $companies = $companies->orderBy('companies.name', 'asc')
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
            'active' => $data['active'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'potential' => $data['potential']
        ]);

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
            'active' => $data['active'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'potential' => $data['potential']
        ]);
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

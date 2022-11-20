<?php

namespace App\Models;

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
        'converted'];

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

    public static function getPotentialCompanies($keyWord, $search_name, $search_nif, $search_type_id, $search_activity_id, $search_province_id){
        $companies = PotentialCompany::select('potential_companies.*', 'company_types.name as type',
            'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province')
            ->leftjoin('company_types', 'company_types.id', '=', 'potential_companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'potential_companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'potential_companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_companies.province_id');
        $companies = $companies->where(function ($query) use ($keyWord){
            $query->orWhere('potential_companies.name', 'LIKE', $keyWord)
                ->orWhere('potential_companies.nif', 'LIKE', $keyWord)
                ->orWhere('company_types.name', 'LIKE', $keyWord)
                ->orWhere('company_activities.name', 'LIKE', $keyWord)
                ->orWhere('potential_companies.email', 'LIKE', $keyWord)
                ->orWhere('potential_companies.telephone', 'LIKE', $keyWord)
                ->orWhere('potential_companies.legal_representative', 'LIKE', $keyWord)
                ->orWhere('potential_companies.dni_legal_representative', 'LIKE', $keyWord)
                ->orWhere('quote', 'LIKE', $keyWord)
                ->orWhere('cnaes.name', 'LIKE', $keyWord)
                ->orWhere('average_template', 'LIKE', $keyWord)
                ->orWhere('potential_companies.iban', 'LIKE', $keyWord)
                ->orWhere('potential_companies.sepa', 'LIKE', $keyWord)
                ->orWhere('potential_companies.b2b', 'LIKE', $keyWord)
                ->orWhere('potential_companies.address', 'LIKE', $keyWord)
                ->orWhere('potential_companies.post_code', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('potential_companies.population', 'LIKE', $keyWord);
        });
        if ($search_name){
            $search_name = '%'.$search_name.'%';
            $companies = $companies->where(function ($query) use ($search_name){
                $query->orWhere('potential_companies.name', 'LIKE', $search_name);
            });
        }
        if ($search_nif){
            $search_nif = '%'.$search_nif.'%';
            $companies = $companies->where(function ($query) use ($search_nif){
                $query->orWhere('potential_companies.nif', 'LIKE', $search_nif);
            });
        }
        if ($search_type_id){
            $companies = $companies->where(function ($query) use ($search_type_id){
                $query->orWhere('potential_companies.company_type_id', $search_type_id);
            });
        }
        if ($search_activity_id){
            $companies = $companies->where(function ($query) use ($search_activity_id){
                $query->orWhere('potential_companies.company_activity_id', $search_activity_id);
            });
        }
        if ($search_province_id){
            $companies = $companies->where(function ($query) use ($search_province_id){
                $query->orWhere('potential_companies.province_id', $search_province_id);
            });
        }
        $companies = $companies->orderBy('potential_companies.name', 'asc')
            ->paginate(10);
        return $companies;
    }

    public static function createPotentialCompany($data){
        $company = PotentialCompany::create([
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
            'advisor_name' => $data['advisor_name']
        ]);

        return $company;
    }

    public static function convertPotentialCompany($id){
        $company = Company::find($id);
        $company->update([
            'converted' => 1
        ]);
    }

    public static function findNif($nif, $id = null){
        $company = Company::where('nif', $nif);
        if ($id){
            $company = $company->where('id', '!=', $id);
        }
        $company = $company->first();

        return $company;
    }

}

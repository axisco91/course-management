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

    public static function getPotentialCompanies(){
        $companies = PotentialCompany::select('potential_companies.*', 'company_types.name as type',
            'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province')
            ->leftjoin('company_types', 'company_types.id', '=', 'potential_companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'potential_companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'potential_companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'potential_companies.province_id')->orderBy('potential_companies.name', 'asc')
            ->get();
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

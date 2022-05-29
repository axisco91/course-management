<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','company_id','irpf','commission','contact_1','contact_2','contact_3'];

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
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'provider_id', 'id');
    }

    public function getProviders($keyWord, $inactiveFilter){
        $providers = Company::select('providers.id as provider_id','providers.irpf', 'providers.commission',
            'providers.contact_1', 'providers.contact_2', 'providers.contact_3', 'companies.*',
            'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province',
            'advisors.name as advisor')
            ->join('providers', 'providers.company_id', '=', 'companies.id')
            ->leftjoin('company_types', 'company_types.id', '=', 'companies.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'companies.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'companies.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'companies.province_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id');
        if ($inactiveFilter != 1) {
            $providers = $providers->where('inactive', 0);
        }

        $providers = $providers->where(function ($query) use ($keyWord){
            $query->orWhere('providers.name', 'LIKE', $keyWord)
                ->orWhere('providers.irpf', 'LIKE', $keyWord)
                ->orWhere('providers.commission', 'LIKE', $keyWord)
                ->orWhere('providers.contact_1', 'LIKE', $keyWord)
                ->orWhere('providers.contact_2', 'LIKE', $keyWord)
                ->orWhere('providers.contact_3', 'LIKE', $keyWord)
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
        })->orderby('name', 'desc')
            ->paginate(10);
        return $providers;
    }

    public function createProvider($data){
        $provider = Provider::create([
            'name' => $data['name'],
            'company_id' => $data['id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3']
        ]);
        return $provider;
    }

    public function updateProvider($id, $data){
        $provider = Provider::find($id);
        $provider->update([
            'name' => $data['name'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3']
        ]);
        return $provider;
    }

    public function convertProvider($id){
        if ($id) {
            $record = Company::find($id);
            $provider = Provider::create([
                'name' => $record['name'],
                'company_id' => $record['id']
            ]);
            return $provider;
        }
    }

}

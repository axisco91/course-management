<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','company_id','irpf','commission','contact_1','contact_2','contact_3', 'nif', 'company_type_id', 'company_activity_id', 'email', 'telephone', 'legal_representative', 'dni_legal_representative', 'cnae_id', 'iban', 'iban', 'sepa', 'b2b', 'address', 'post_code', 'population_id', 'province_id', 'prpulation', 'active'];

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
        $providers = Provider::select('providers.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province')
            ->leftjoin('company_types', 'company_types.id', '=', 'providers.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'providers.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'providers.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'providers.province_id');

        if ($inactiveFilter != 1) {
            $providers = $providers->where('active', 1);
        }

        $providers = $providers->where(function ($query) use ($keyWord){
            $query->orWhere('providers.name', 'LIKE', $keyWord)
                ->orWhere('providers.irpf', 'LIKE', $keyWord)
                ->orWhere('providers.commission', 'LIKE', $keyWord)
                ->orWhere('providers.contact_1', 'LIKE', $keyWord)
                ->orWhere('providers.contact_2', 'LIKE', $keyWord)
                ->orWhere('providers.contact_3', 'LIKE', $keyWord)
                ->orWhere('providers.nif', 'LIKE', $keyWord)
                ->orWhere('company_types.name', 'LIKE', $keyWord)
                ->orWhere('company_activities.name', 'LIKE', $keyWord)
                ->orWhere('providers.email', 'LIKE', $keyWord)
                ->orWhere('providers.telephone', 'LIKE', $keyWord)
                ->orWhere('providers.legal_representative', 'LIKE', $keyWord)
                ->orWhere('providers.dni_legal_representative', 'LIKE', $keyWord)
                ->orWhere('cnaes.name', 'LIKE', $keyWord)
                ->orWhere('providers.address', 'LIKE', $keyWord)
                ->orWhere('providers.post_code', 'LIKE', $keyWord)
                ->orWhere('providers.name', 'LIKE', $keyWord)
                ->orWhere('providers.population', 'LIKE', $keyWord)
                ->orWhere('providers.active', 'LIKE', $keyWord);
        })->orderBy('providers.name', 'desc')
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

    public function findNif($nif, $id = null){
        $provider = Provider::where('nif', $nif);
        if ($id){
            $provider = $provider->where('id', '!=', $id);
        }
        $provider = $provider->first();

        return $provider;
    }

}

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

    public static function getProviders(){
        $providers = Provider::select('providers.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'providers.id as value', 'providers.name as label')
            ->leftjoin('company_types', 'company_types.id', '=', 'providers.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'providers.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'providers.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'providers.province_id')
            ->orderBy('providers.name', 'desc')
            ->get();
        foreach($providers as $provider) {
            $training_action = TrainingAction::where('provider_id', $provider->id)->first();
            if ($training_action) {
                $provider['used'] = true;
            } else {
                $provider['used'] = false;
            }
        }
        return $providers;
    }

    public static function getProvider($id){
        $provider = Provider::select('providers.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'providers.id as value', 'providers.name as label')
            ->leftjoin('company_types', 'company_types.id', '=', 'providers.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'providers.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'providers.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'providers.province_id')
            ->where('providers.id', $id)
            ->first();
        $training_action = TrainingAction::where('provider_id', $provider->id)->first();
        if ($training_action) {
            $provider['used'] = true;
        } else {
            $provider['used'] = false;
        }
        return $provider;
    }

    public static function createProvider($data){
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
            'active' => $data['active']
        ]);
        return $provider;
    }

    public static function updateProvider($id, $data){
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
            'active' => $data['active']
        ]);
        return $provider;
    }

    public static function convertProvider($id){
        if ($id) {
            $record = Company::find($id);
            $provider = Provider::create([
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
            return $provider;
        }
    }

    public static function findNif($nif, $id = null){
        $provider = Provider::where('nif', $nif);
        if ($id){
            $provider = $provider->where('id', '!=', $id);
        }
        $provider = $provider->first();

        return $provider;
    }

}

<?php

namespace App\Models;

use App\Services\ProviderService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Provider extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','company_id','irpf','commission','contact_1','contact_2','contact_3', 'nif', 'company_type_id', 'company_activity_id', 'email', 'telephone', 'legal_representative', 'dni_legal_representative', 'cnae_id', 'iban', 'iban', 'sepa', 'b2b', 'address', 'post_code', 'population_id', 'province_id', 'prpulation', 'active', 'main_company_id'];

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

    public function scopeGetProvider($query, $mainCompanyId)
    {
        return $query
            ->select(
                'providers.*',
                'providers.id as value',
                'providers.name as label',
                'company_types.name as type',
                'company_activities.name as activity',
                'cnaes.name as cnae',
                'provinces.name as province',
                'companies.quote as quote',
                'companies.average_template as average_template',
                'users.id as collaborator_id',
                DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"),
                'advisors.name as advisor',
                'companies.advisor_id as advisor_id'
            )
            ->leftJoin('company_types', 'company_types.id', '=', 'providers.company_type_id')
            ->leftJoin('company_activities', 'company_activities.id', '=', 'providers.company_activity_id')
            ->leftJoin('cnaes', 'cnaes.id', '=', 'providers.cnae_id')
            ->leftJoin('provinces', 'provinces.id', '=', 'providers.province_id')
            ->leftJoin('companies', 'companies.id', '=', 'providers.company_id')
            ->leftJoin('users', 'users.id', '=', 'companies.collaborator_id')
            ->leftJoin('advisors', 'advisors.id', '=', 'companies.advisor_id')

            // LEFT JOIN a training_actions para calcular USED
            ->leftJoin('training_actions', function($join) use ($mainCompanyId) {
                $join->on('training_actions.provider_id', '=', 'providers.id')
                    ->where('training_actions.main_company_id', '=', $mainCompanyId);
            })

            ->selectRaw('CASE WHEN training_actions.id IS NULL THEN false ELSE true END as used')

            ->where('providers.main_company_id', $mainCompanyId)
            ->orderBy('providers.name', 'desc')
            ->groupBy('providers.id');
    }

    public static function convertProvider($id, $record){
        if ($id) {
            return Provider::create([
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
                'main_company_id' => $record['main_company_id'],
            ]);
        }
    }

    public static function findNif($nif, $mainCompanyId, $id = null){
        $provider = Provider::where('nif', $nif)
            ->where('main_company_id', $mainCompanyId);
        if ($id){
            $provider = $provider->where('id', '!=', $id);
        }
        $provider = $provider->first();

        return $provider;
    }

    public static function createWithService($data)
    {
        $service = app(ProviderService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(ProviderService::class);
        return $service->update($this, $data);
    }
}

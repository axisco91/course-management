<?php

namespace App\Models;

use App\Services\CompanyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'nif',
        'company_type_id',
        'company_activity_id',
        'email',
        'telephone',
        'legal_representative',
        'dni_legal_representative',
        'regimen',
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
        'potential',
        'population_code',
        'agreement',
        'main_company_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Company.php

    public function advisors() // ✅ una empresa puede tener varios advisors "propios" (company_id en advisors)
    {
        return $this->hasMany(Advisor::class, 'company_id', 'id');
    }

    public function advisor() // ✅ advisor asignado a la empresa (companies.advisor_id -> advisors.id)
    {
        return $this->belongsTo(Advisor::class, 'advisor_id', 'id');
    }

    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id', 'id');
    }

    public function advisorCompany()
    {
        return $this->hasMany(Advisor::class, 'company_id', 'id');
    }

    public function provider()
    {
        return $this->hasMany(Provider::class, 'company_id', 'id');
    }

    public function cnae() // ✅ companies.cnae_id -> cnaes.id
    {
        return $this->belongsTo(Cnae::class, 'cnae_id', 'id');
    }

    public function companyActivity() // ✅ companies.company_activity_id -> company_activities.id  (ojo al campo)
    {
        return $this->belongsTo(CompanyActivity::class, 'company_activity_id', 'id');
    }

    public function companyObservations()
    {
        return $this->hasMany(CompanyObservation::class, 'company_id', 'id');
    }

    public function companyType() // ✅ companies.company_type_id -> company_types.id
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id', 'id');
    }

    public function population() // ✅ companies.population_id -> populations.id
    {
        return $this->belongsTo(Population::class, 'population_id', 'id');
    }

    public function province() // ✅ companies.province_id -> provinces.id
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'company_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeCompany($query, $mainCompanyId)
    {
        return $query
            ->with([
                'companyType:id,name',
                'companyActivity:id,name',
                'cnae:id,name',
                'population:id,name',
                'province:id,name',
                'advisor:id,name',
                'collaborator:id,name,surname',
                'provider:id,name',
                'advisorCompany:id,name',
            ])

            // flags eficientes (NO joins)
            ->withExists([
                'provider as is_provider',
                'advisorCompany as is_advisor',
            ])

            ->select([
                'companies.*',
                // STATUS
                DB::raw("
                CASE
                    WHEN companies.potential = 1 THEN 'Potencial'
                    WHEN companies.active = 0 THEN 'Inactivo'
                    ELSE 'Activo'
                END AS status
            ")
            ])
            ->where('companies.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId)
    {
        return $query->where('companies.main_company_id', $mainCompanyId);
    }

    public function scopeActive($query)
    {
        return $query->where('companies.active', 1);
    }

    /**
     * Scope para buscar por NIF dentro de COMPANIES (sin potencial)
     *
     * Uso:
     *   Company::findNifScope($nif)->first();
     *   Company::findNifScope($nif, $excludeId)->first();
     */
    public function scopeFindNifScope($query, string $nif, ?int $excludeId = null)
    {
        $query->where('nif', $nif);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos estáticos (negocio / helpers)
    |--------------------------------------------------------------------------
    */

    public static function changeState($id)
    {
        $company = self::find($id);

        if (!$company) {
            return null;
        }

        $company->update([
            'active' => $company->active ? 0 : 1,
        ]);

        return $company->active;
    }

    /**
     * Busca una company o potential_company por NIF.
     * Internamente usa el scope para Company.
     */
    public static function findNif($nif, $id = null)
    {
        // Primero buscamos en Company
        $company = self::findNifScope($nif, $id)->first();

        if (!$company) {
            // Luego en PotentialCompany
            $potential = PotentialCompany::where('nif', $nif);

            if ($id) {
                $potential->where('id', '!=', $id);
            }

            $company = $potential->first();
        }

        return $company;
    }

    public static function convertCompany($potential_id, $data)
    {
        $company = self::createCompany($data);

        $potential_observations = PotentialCompanyObservation::where('potential_company_id', $potential_id)->get();

        foreach ($potential_observations as $potential_observation) {
            $obsData = [
                'company_id'  => $company->id,
                'observation' => $potential_observation->observation,
            ];
            CompanyObservation::createCompanyObservation($obsData);
        }

        $potential = PotentialCompany::find($potential_id);

        if ($potential) {
            $potential->delete();
        }
    }

    public static function createWithService($data)
    {
        $service = app(CompanyService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(CompanyService::class);

        return $service->update($this, $data);
    }
}

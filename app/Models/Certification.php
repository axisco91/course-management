<?php

namespace App\Models;

use App\Services\CertificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Certification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'total_hours',
        'code',
        'professional_family_id',
        'professional_area_id',
        'level',
        'face_to_face_hours',
        'teletraining_hours',
        'main_company_id',
        'active',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function trainingContracts()
    {
        return $this->belongsToMany(TrainingContract::class, 'training_contract_elements');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes base
    |--------------------------------------------------------------------------
    */

    public function scopeFilterMainCompany($query, $mainCompanyId)
    {
        return $query->where('certifications.main_company_id', $mainCompanyId);
    }

    public function scopeActive($query)
    {
        return $query->where('certifications.active', 1);
    }

    /**
     * Scope base para traer certificaciones con meta (familia + área + used)
     */
    protected function scopeWithMeta($query)
    {
        return $query
            ->select(
                'certifications.*',
                'professional_families.name as family',
                'professional_areas.name as area'
            )
            ->leftJoin(
                'professional_families',
                'professional_families.id',
                '=',
                'certifications.professional_family_id'
            )
            ->leftJoin(
                'professional_areas',
                'professional_areas.id',
                '=',
                'certifications.professional_area_id'
            )
            // "used" = existe algún training_contract asociado
            ->withExists(['trainingContracts as used']);
    }

    /**
     * Antes: scopeForMainCompanyWithMeta
     */
    public function scopeForMainCompanyWithMeta($query, int $mainCompanyId)
    {
        return $query
            ->withMeta()
            ->FilterMainCompany($mainCompanyId);
    }

    /**
     * Antes: scopeCertification
     * Es exactamente lo mismo, así que lo dejamos como alias
     */
    public function scopeCertification($query, int $mainCompanyId)
    {
        return $query->forMainCompanyWithMeta($mainCompanyId);
    }

    /*
    |--------------------------------------------------------------------------
    | Certificaciones no incluidas en un training contract
    |--------------------------------------------------------------------------
    |
    | Creamos un scope + mantenemos tu método static para no romper llamadas.
    |
    */

    public function scopeNotInTrainingContract($query, $trainingContractId, $mainCompanyId)
    {
        return $query
            ->select(
                'certifications.*',
                'certifications.id as value',
                DB::raw("CONCAT(certifications.name,' (', certifications.total_hours,' horas)') as label")
            )
            ->Active()
            ->FilterMainCompany($mainCompanyId)
            ->whereNotIn('certifications.id', function ($sub) use ($trainingContractId, $mainCompanyId) {
                $sub->from('training_contract_elements')
                    ->select('certification_id')
                    ->where('training_contract_id', $trainingContractId)
                    ->where('main_company_id', $mainCompanyId)
                    ->whereNotNull('certification_id');
            });
    }

    /*
  |--------------------------------------------------------------------------
  | Helpers create/update (los dejo estáticos)
  |--------------------------------------------------------------------------
  */
    public static function createWithService($data)
    {
        $service = app(CertificationService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(CertificationService::class);
        return $service->update($this, $data);
    }

    /*
   |--------------------------------------------------------------------------
   | Horas
   |--------------------------------------------------------------------------
   */
    public function updateHours($exam_difference, $tutoring_difference, $teletraining_difference)
    {
        $total_hours = $this->total_hours
            + $exam_difference
            + $tutoring_difference
            + $teletraining_difference;

        // Aquí solo actualizamos ESTA certificación.
        // Si quisieras propagar a algo más (módulos, unidades...), se haría por otras relaciones,
        // pero tu código original tenía un $this->certifications() que no existe y causaría error/recursión.
        $this->update([
            'total_hours' => $total_hours,
        ]);
    }
}

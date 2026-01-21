<?php

namespace App\Models;

use App\Services\TrainingUnitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','total_hours','active','face_to_face_hours','tutoring_hours',
        'teletraining_hours','exam_hours','formative_unit','main_company_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'training_units_modules');
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class,'certification_elements');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes existentes
    |--------------------------------------------------------------------------
    */
    public function scopeFilterMainCompany($q, $mainCompanyId)
    {
        return $q->where('training_units.main_company_id', $mainCompanyId);
    }

    /*
    |--------------------------------------------------------------------------
    | Nuevos SCOPES en vez de métodos static
    |--------------------------------------------------------------------------
    */

    // === getTrainingUnits($mainCompanyId)
    public function scopeGetTrainingUnits($q, $mainCompanyId)
    {
        return $q->select('*', 'id as value', 'name as label')
            ->FilterMainCompany($mainCompanyId);
    }

    // === getTrainingUnit($id, $mainCompanyId)
    public function scopeGetTrainingUnit($q, $id, $mainCompanyId)
    {
        return $q->select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->FilterMainCompany($mainCompanyId);
    }

    // === getProviderTrainingUnit(...)
    public function scopeGetProviderTrainingUnit($q, $providerId, $name, $action, $mainCompanyId)
    {
        return $q->where('provider_id', $providerId)
            ->where('training_units.main_company_id', $mainCompanyId)
            ->where(function ($qq) use ($name) {
                $qq->orWhere('name', 'LIKE', $name);
            })
            ->where(function ($qq) use ($action) {
                $qq->orWhere('formative_unit', 'LIKE', $action);
            });
    }

    // === getTrainingUnitsNotInModule(...)
    public function scopeGetTrainingUnitsNotInModule($q, $moduleId, $mainCompanyId)
    {
        return $q->select('training_units.*', 'training_units.id as value', 'training_units.name as label')
            ->where('training_units.active', 1)
            ->FilterMainCompany($mainCompanyId)
            ->whereNotIn('training_units.id', function ($sub) use ($moduleId, $mainCompanyId) {
                $sub->from('training_units_modules')
                    ->select('training_unit_id')
                    ->where('module_id', $moduleId)
                    ->where('main_company_id', $mainCompanyId);
            });
    }

    // === getTrainingUnitsNotInCertification(...)
    public function scopeGetTrainingUnitsNotInCertification($q, $certificationId, $mainCompanyId)
    {
        return $q->select('training_units.*', 'training_units.id as value', 'training_units.name as label')
            ->where('training_units.active', 1)
            ->FilterMainCompany($mainCompanyId)
            ->whereNotIn('training_units.id', function ($sub) use ($certificationId, $mainCompanyId) {
                $sub->from('certification_elements')
                    ->select('training_unit_id')
                    ->where('certification_id', $certificationId)
                    ->where('main_company_id', $mainCompanyId)
                    ->whereNotNull('training_unit_id');
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */
    public static function createWithService($data)
    {
        return app(TrainingUnitService::class)->create($data);
    }

    public function updateWithService($data)
    {
        return app(TrainingUnitService::class)->update($this, $data);
    }
}

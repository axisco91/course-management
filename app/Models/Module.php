<?php

namespace App\Models;

use App\Services\ModuleService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'total_hours',
        'active',
        'face_to_face_hours',
        'tutoring_hours',
        'teletraining_hours',
        'exam_hours',
        'formative_module',
        'main_company_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function trainingUnits()
    {
        return $this->belongsToMany(
            TrainingUnit::class,
            'training_units_modules',
            'module_id',
            'training_unit_id'
        );
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class, 'certification_elements');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeFilterMainCompany($query, $mainCompanyId)
    {
        return $query->where('modules.main_company_id', $mainCompanyId);
    }

    /**
     * Equivalente a getModules($mainCompanyId)
     * Devuelve query con:
     *  - modules.*
     *  - value = id
     *  - label = name
     *  - used = 1/0 si está en alguna certification_elements
     */
    public function scopeGetModules($query, $mainCompanyId)
    {
        return $query
            ->selectRaw('modules.*, modules.id as value, modules.name as label')
            ->selectRaw(
                'EXISTS (
                    SELECT 1
                    FROM certification_elements ce
                    WHERE ce.module_id = modules.id
                      AND ce.main_company_id = ?
                ) as used',
                [$mainCompanyId]
            )
            ->where('modules.main_company_id', $mainCompanyId);
    }

    /**
     * Equivalente a getModule($id, $mainCompanyId)
     */
    public function scopeGetModule($query, $id, $mainCompanyId)
    {
        return $query
            ->selectRaw('modules.*, modules.id as value, modules.name as label')
            ->selectRaw(
                'EXISTS (
                    SELECT 1
                    FROM certification_elements ce
                    WHERE ce.module_id = modules.id
                      AND ce.main_company_id = ?
                ) as used',
                [$mainCompanyId]
            )
            ->where('modules.id', $id)
            ->where('modules.main_company_id', $mainCompanyId);
    }

    /**
     * Módulos disponibles para una certificación (no ya vinculados a ella)
     */
    public function scopeAvailableForCertification($query, int $certificationId, int $mainCompanyId)
    {
        return $query
            ->where('modules.main_company_id', $mainCompanyId)
            ->where('modules.active', 1)
            ->whereNotIn('modules.id', function ($sub) use ($certificationId, $mainCompanyId) {
                $sub->from('certification_elements')
                    ->select('module_id')
                    ->where('certification_id', $certificationId)
                    ->where('main_company_id', $mainCompanyId)
                    ->whereNotNull('module_id');
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Lógica de horas
    |--------------------------------------------------------------------------
    */

    public function updateHours($exam_difference, $tutoring_difference, $teletraining_difference)
    {
        $exam_hours         = $this->exam_hours + $exam_difference;
        $tutoring_hours     = $this->tutoring_hours + $tutoring_difference;
        $face_to_face_hours = $this->face_to_face_hours + $exam_difference + $tutoring_difference;
        $teletraining_hours = $this->teletraining_hours + $teletraining_difference;
        $total_hours        = $this->total_hours + $exam_difference + $tutoring_difference + $teletraining_difference;

        $certifications = $this->certifications()->get();
        foreach ($certifications as $certification) {
            $certification->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
        }

        $this->update([
            'exam_hours'         => $exam_hours,
            'tutoring_hours'     => $tutoring_hours,
            'face_to_face_hours' => $face_to_face_hours,
            'teletraining_hours' => $teletraining_hours,
            'total_hours'        => $total_hours,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    public static function createWithService($data)
    {
        $service = app(ModuleService::class);
        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(ModuleService::class);
        return $service->update($this, $data);
    }
}

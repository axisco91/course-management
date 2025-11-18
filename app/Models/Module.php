<?php

namespace App\Models;

use App\Services\ModuleService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'total_hours', 'active', 'face_to_face_hours', 'tutoring_hours', 'teletraining_hours', 'exam_hours', 'formative_module', 'main_company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingUnits()
    {
        return $this->belongsToMany(TrainingUnit::class,'training_units_modules', 'module_id', 'training_unit_id');
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class,'certification_elements');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('modules.main_company_id', $mainCompanyId);
    }

    public static function getModules($mainCompanyId) {
        $modules = Module::select('*', 'id as value', 'name as label')
            ->where('main_company_id', $mainCompanyId)
            ->get();
        foreach ($modules as $module) {
            $element = CertificationElement::where('module_id', $module->id)->first();
            if ($element) {
                $module['used'] = true;
            } else {
                $module['used'] = false;
            }
        }
        return $modules;
    }

    public static function getModule($id, $mainCompanyId){
        $module = Module::select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();
        $element = CertificationElement::where('module_id', $module->id)
            ->where('main_company_id', $mainCompanyId)
            ->first();
        if ($element) {
            $module['used'] = true;
        } else {
            $module['used'] = false;
        }
        return $module;
    }

    public static function getModulesNotInCertification($id, $mainCompanyId){
        $certifications_modules = CertificationElement::where('certification_elements.certification_id', $id)
            ->where('certification_elements.main_company_id', $mainCompanyId)
            ->whereNotNull('module_id')
            ->pluck('module_id');
        $module = Module::select('modules.*', 'modules.id as value', 'modules.name as label')
            ->whereNotIn('id', $certifications_modules)
            ->where('modules.main_company_id', $mainCompanyId)
            ->where('active', 1)->get();
        return $module;
    }

    public function updateHours($exam_difference, $tutoring_difference, $teletraining_difference){
        $exam_hours = $this->exam_hours + $exam_difference;
        $tutoring_hours = $this->tutoring_hours + $tutoring_difference;
        $face_to_face_hours = $this->face_to_face_hours + $exam_difference + $tutoring_difference;
        $teletraining_hours = $this->teletraining_hours + $teletraining_difference;
        $total_hours = $this->total_hours + $exam_difference + $tutoring_difference + $teletraining_difference;
        $certifications = $this->certifications()->get();
        foreach ($certifications as $certification){
            $certification->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
        }
        $this->update([
            'exam_hours' => $exam_hours,
            'tutoring_hours' => $tutoring_hours,
            'face_to_face_hours' => $face_to_face_hours,
            'teletraining_hours' => $teletraining_hours,
            'total_hours' => $total_hours
        ]);
    }

    public static function createWithService($data)
    {
        $service = app(ModuleService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(ModuleService::class);
        return $service->update($this, $data);
    }
}

<?php

namespace App\Models;

use App\Services\TrainingUnitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class TrainingUnit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'total_hours', 'active', 'face_to_face_hours', 'tutoring_hours', 'teletraining_hours', 'exam_hours', 'formative_unit', 'main_company_id'];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'training_units_modules');
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class,'certification_elements');
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('training_units.main_company_id', $mainCompanyId);
    }

    public static function getTrainingUnits($mainCompanyId) {
        $training_units = TrainingUnit::select('*', 'id as value', 'name as label')
            ->FilterMainCompany($mainCompanyId)
            ->get();
        foreach ($training_units as $training_unit) {
            $element = TrainingUnitsModule::where('training_unit_id', $training_unit->id)->first();
            if ($element) {
                $training_unit['used'] = true;
            } else {
                $training_unit['used'] = false;
            }
        }
        return $training_units;
    }

    public static function getTrainingUnit($id, $mainCompanyId) {
        $training_unit = TrainingUnit::select('*', 'id as value', 'name as label')->where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();
        $element = TrainingUnitsModule::where('training_unit_id', $training_unit->id)->first();
        if ($element) {
            $training_unit['used'] = true;
        } else {
            $training_unit['used'] = false;
        }
        return $training_unit;
    }

    public static function getProviderTrainingUnit($id, $search_training_units_name, $search_training_units_action, $mainCompanyId)
    {
        $training_units = TrainingUnit::where('provider_id', $id)
            ->where('training_units.main_company_id', $mainCompanyId)
            ->where(function ($query) use ($search_training_units_name) {
                $query->orWhere('name', 'LIKE', $search_training_units_name);
            })->where(function ($query) use ($search_training_units_action) {
                $query->orWhere('formative_unit', 'LIKE', $search_training_units_action);
            })->get();

        return $training_units;
    }

    public static function getTrainingUnitsNotInModule($moduleId, $mainCompanyId){
        $training_units = TrainingUnit::leftjoin('training_units_modules', 'training_units_modules.training_unit_id', 'training_units.id')
            ->where('training_units_modules.module_id', $moduleId)
            ->where('training_units_modules.main_company_id', $mainCompanyId)
            ->pluck('training_units.id');
        $not_in_module = TrainingUnit::select('training_units.*', 'training_units.id as value', 'training_units.name as label')
            ->where('active', 1)
            ->where('training_units.main_company_id', $mainCompanyId)
            ->whereNotIn('training_units.id', $training_units)->get();
        return $not_in_module;
    }

    public static function getTrainingUnitsNotInCertification($id, $mainCompanyId){
        $certificationUnits = CertificationElement::where('certification_elements.certification_id', $id)
            ->whereNotNull('training_unit_id')
            ->where('certification_elements.main_company_id', $mainCompanyId)
            ->pluck('training_unit_id');
        $units = TrainingUnit::select('training_units.*', 'training_units.id as value', 'training_units.name as label')
            ->whereNotIn('id', $certificationUnits)
            ->where('training_units.main_company_id', $mainCompanyId)
            ->where('active', 1)->get();
        return $units;
    }

    public static function createWithService($data)
    {
        $service = app(TrainingUnitService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TrainingUnitService::class);
        return $service->update($this, $data);
    }
}

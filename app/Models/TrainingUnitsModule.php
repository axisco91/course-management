<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingUnitsModule extends Model
{
    use HasFactory;

    protected $fillable = ['training_unit_id', 'module_id'];

    public function scopeGetTrainingUnitModules($query, $moduleId, $mainCompanyId)
    {
        return $query
            ->select(
                'training_units_modules.*',
                'training_units.name',
                'training_units.formative_unit'
            )
            ->leftJoin('training_units', 'training_units.id', '=', 'training_units_modules.training_unit_id')
            ->where('training_units_modules.module_id', $moduleId)
            ->where('training_units_modules.main_company_id', $mainCompanyId);
    }

    public static function createTrainingUnitModule($moduleId, $training_unit_id, $mainCompanyId){
        $training_unit_module = TrainingUnitsModule::where('module_id', $moduleId)
            ->where('training_unit_id', $training_unit_id)
            ->where('main_company_id', $mainCompanyId)
            ->first();
        if (!$training_unit_module){
            $training_unit_module = TrainingUnitsModule::create([
                'module_id' => $moduleId,
                'training_unit_id' => $training_unit_id,
                'main_company_id' => $mainCompanyId
            ]);
            $training_unit = TrainingUnit::find($training_unit_id);
            $module = Module::find($moduleId);
            $module->update([
                'face_to_face_hours' => $module['face_to_face_hours'] + $training_unit['face_to_face_hours'],
                'teletraining_hours' => $module['teletraining_hours'] + $training_unit['teletraining_hours'],
                'exam_hours' => $module['exam_hours'] + $training_unit['exam_hours'],
                'tutoring_hours' => $module['tutoring_hours'] + $training_unit['tutoring_hours'],
                'total_hours' => $module['total_hours'] + $training_unit['total_hours']
            ]);
        }
        return $training_unit_module;
    }

    public static function deleteTrainingUnitModule($id, $mainCompanyId){
        $training_unit_module = TrainingUnitsModule::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();
        $training_unit = TrainingUnit::find($training_unit_module['training_unit_id']);
        $training_unit['value'] = $training_unit->id;
        $training_unit['label'] = $training_unit->name;
        $module = Module::find($training_unit_module['module_id']);
        $module->update([
            'face_to_face_hours' => $module['face_to_face_hours'] - $training_unit['face_to_face_hours'],
            'teletraining_hours' => $module['teletraining_hours'] - $training_unit['teletraining_hours'],
            'exam_hours' => $module['exam_hours'] - $training_unit['exam_hours'],
            'tutoring_hours' => $module['tutoring_hours'] - $training_unit['tutoring_hours'],
            'total_hours' => $module['total_hours'] - $training_unit['total_hours']
        ]);
        $training_unit_module->delete();
        return [
            'module' => $module,
            'training_unit' => $training_unit
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingUnitsModule extends Model
{
	use HasFactory;

    protected $fillable = ['training_unit_id', 'module_id'];

    public static function getTrainingUnitModules($module_id){
        $training_unit = TrainingUnitsModule::select('training_units_modules.*', 'training_units.name', 'training_units.formative_unit')
            ->leftjoin('training_units', 'training_units.id', '=', 'training_units_modules.training_unit_id')
            ->where('module_id', $module_id)->get();
        return $training_unit;
    }

    public static function createTrainingUnitModule($module_id, $training_unit_id){
        $training_unit_module = TrainingUnitsModule::where('module_id', $module_id)
            ->where('training_unit_id', $training_unit_id)->first();
        if (!$training_unit_module){
            $training_unit_module = TrainingUnitsModule::create([
                'module_id' => $module_id,
                'training_unit_id' => $training_unit_id
            ]);
            $training_unit = TrainingUnit::find($training_unit_id);
            $module = Module::find($module_id);
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

    public static function deleteTrainingUnitModule($module_id, $training_unit_module_id){
        $training_unit_module = TrainingUnitsModule::find($training_unit_module_id);
        $training_unit = TrainingUnit::find($training_unit_module['training_unit_id']);
        $module = Module::find($module_id);
        $module->update([
            'face_to_face_hours' => $module['face_to_face_hours'] - $training_unit['face_to_face_hours'],
            'teletraining_hours' => $module['teletraining_hours'] - $training_unit['teletraining_hours'],
            'exam_hours' => $module['exam_hours'] - $training_unit['exam_hours'],
            'tutoring_hours' => $module['tutoring_hours'] - $training_unit['tutoring_hours'],
            'total_hours' => $module['total_hours'] - $training_unit['total_hours']
        ]);
        $training_unit_module->delete();
    }
}

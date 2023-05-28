<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CertificationElement extends Model
{
	use HasFactory;

    protected $fillable = ['certification_id', 'training_unit_id', 'module_id'];

    public static function getCertificationElements($certification_id){
        $certification_elements = CertificationElement::select('certification_elements.*', DB::raw('IFNULL(training_units.formative_unit, modules.formative_module) AS formative_action'),DB::raw('IFNULL(training_units.name, modules.name) AS element'), 'training_units.name as training_unit_name',
            'training_units.exam_hours as training_unit_exam_hours', 'modules.exam_hours as module_exam_hours',
            'training_units.tutoring_hours as training_unit_tutoring_hours', 'modules.tutoring_hours as module_tutoring_hours',
            'training_units.face_to_face_hours as training_unit.face_to_face_hours', 'modules.face_to_face_hours as module_face_to_face_hours',
            'training_units.teletraining_hours as training_unit_teletraining_hours', 'modules.teletraining_hours as module_teletraining_hours',
            'training_units.total_hours as training_unit.total_hours', 'modules.total_hours as module_total_hours',
            'training_units.formative_unit', 'modules.name as module_name', 'modules.formative_module', 'certification_elements.id as value')
            ->leftjoin('training_units', 'training_units.id', '=', 'certification_elements.training_unit_id')
            ->leftjoin('modules', 'modules.id', '=', 'certification_elements.module_id')
            ->where('certification_elements.certification_id', $certification_id)->get();
        return $certification_elements;
    }

    public static function getCertificationElement($id){
        $certification_element = CertificationElement::select('certification_elements.*', DB::raw('IFNULL(training_units.formative_unit, modules.formative_module) AS formative_action'),DB::raw('IFNULL(training_units.name, modules.name) AS element'), 'training_units.name as training_unit_name',
            'training_units.exam_hours as training_unit_exam_hours', 'modules.exam_hours as module_exam_hours',
            'training_units.tutoring_hours as training_unit_tutoring_hours', 'modules.tutoring_hours as module_tutoring_hours',
            'training_units.face_to_face_hours as training_unit.face_to_face_hours', 'modules.face_to_face_hours as module_face_to_face_hours',
            'training_units.teletraining_hours as training_unit_teletraining_hours', 'modules.teletraining_hours as module_teletraining_hours',
            'training_units.total_hours as training_unit.total_hours', 'modules.total_hours as module_total_hours',
            'training_units.formative_unit', 'modules.name as module_name', 'modules.formative_module', 'certification_elements.id as value')
            ->leftjoin('training_units', 'training_units.id', '=', 'certification_elements.training_unit_id')
            ->leftjoin('modules', 'modules.id', '=', 'certification_elements.module_id')
            ->where('certification_elements.id', $id)->first();
        return $certification_element;
    }

    public static function createCertificationElement($certification_id, $element_id, $type){
        $hours = 0;
        $certification_element = null;
        if ($type == 'training_unit_id'){
            $certification_element = CertificationElement::where('training_unit_id', $element_id)
                ->where('certification_id', $certification_id)->first();
            if (!$certification_element){
                $certification_element = CertificationElement::create([
                    'certification_id' => $certification_id,
                    'training_unit_id' => $element_id
                ]);
                $training_unit = TrainingUnit::find($element_id);
                $hours = $training_unit['total_hours'];
            }
        } else if ($type == 'module_id'){
            $certification_element = CertificationElement::where('module_id', $element_id)
                ->where('certification_id', $certification_id)->first();
            if (!$certification_element){
                $certification_element = CertificationElement::create([
                    'certification_id' => $certification_id,
                    'module_id' => $element_id,
                ]);
                $module = Module::find($element_id);
                $hours = $module['total_hours'];
            }
        }

        $certification = Certification::find($certification_id);
        $certification->update([
            'total_hours' => $certification['total_hours'] + $hours
        ]);
        return $certification_element;
    }

    public static function deleteCertificationElement($id){
        $certification_element = CertificationElement::find($id);
        $certification = Certification::find($certification_element->certification_id);
         $hours = 0;
        if ($certification){
            if ($certification_element){
                if ($certification_element->training_unit_id) {
                    $training_unit = TrainingUnit::find($certification_element->training_unit_id);
                    $hours = $training_unit['total_hours'];
                } else if($certification_element->module_id) {
                    $module = Module::find($certification_element->module_id);
                    $hours = $module['total_hours'];
                }
                $certification_element->delete();
            }
            $certification->update([
                'total_hours' => $certification['total_hours'] - $hours
            ]);
        }
    }
}

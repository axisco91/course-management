<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificationElement extends Model
{
	use HasFactory;

    protected $fillable = ['certification_id', 'training_unit_id', 'module_id'];

    public static function getCertificationElement($certification_id){
        $certification_elements = CertificationElement::select('certification_elements.*', 'training_units.name as training_unit_name',
            'training_units.formative_unit', 'modules.name as module_name', 'modules.formative_module')
            ->leftjoin('training_units', 'training_units.id', '=', 'certification_elements.training_unit_id')
            ->leftjoin('modules', 'modules.id', '=', 'certification_elements.module_id')
            ->where('certification_id', $certification_id)->get();
        return $certification_elements;
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

    public static function deleteCertificationElement($certification_id, $id){
        $certification = Certification::find($certification_id);
        $certification_element = CertificationElement::find($id);
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
                'total_hours' => - $hours
            ]);
        }
    }
}

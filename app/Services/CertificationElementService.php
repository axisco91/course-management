<?php

namespace App\Services;

use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\Module;
use App\Models\TrainingUnit;

class CertificationElementService
{
    /**
     * Función para crear una observación
     * @param array $data
     * @return mixed
     */
    public function create($certification_id, $elementId, $type, $mainCompanyId)
    {
        $hours = 0;
        $face_to_face_hours = 0;
        $teletraining_hours = 0;
        $certification_element = null;
        if ($type == 'training_unit_id'){
            $certification_element = CertificationElement::where('training_unit_id', $elementId)
                ->where('certification_id', $certification_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if (!$certification_element){
                $certification_element = CertificationElement::create([
                    'certification_id' => $certification_id,
                    'training_unit_id' => $elementId,
                    'main_company_id' => $mainCompanyId,
                ]);
                $training_unit = TrainingUnit::find($elementId);
                $hours = $training_unit['total_hours'];
                $face_to_face_hours = $training_unit['face_to_face_hours'];
                $teletraining_hours = $training_unit['teletraining_hours'];
            }
        } else if ($type == 'module_id'){
            $certification_element = CertificationElement::where('module_id', $elementId)
                ->where('certification_id', $certification_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$certification_element){
                $certification_element = CertificationElement::create([
                    'certification_id' => $certification_id,
                    'module_id' => $elementId,
                    'main_company_id' => $mainCompanyId,
                ]);
                $module = Module::where('id', $elementId)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if ($module){
                    $hours = $module['total_hours'];
                    $face_to_face_hours = $module['face_to_Face_hours'];
                    $teletraining_hours = $module['teletraining_hours'];
                }
            }
        }

        $certification = Certification::where('id', $certification_id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if ($certification){
            $certification->update([
                'total_hours' => $certification['total_hours'] + $hours,
                'face_to_face_hours' => $certification['face_to_face_hours'] + $face_to_face_hours,
                'teletraining_hours' => $certification['teletraining_hours'] + $teletraining_hours
            ]);
        }
        return $certification_element;
    }

    /**
     * Función para editar una observación
     */
    public function delete($id, $mainCompanyId) {
        $certification_element = CertificationElement::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        $certification = Certification::where('id', $certification_element->certification_id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        $hours = 0;
        $face_to_face_hours = 0;
        $teletraining_hours = 0;
        if ($certification){
            if ($certification_element){
                if ($certification_element->training_unit_id) {
                    $training_unit = TrainingUnit::where('id', $certification_element->training_unit_id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();
                    $hours = $training_unit['total_hours'];
                    $face_to_face_hours = $training_unit['face_to_face_hours'];
                    $teletraining_hours = $training_unit['teletraining_hours'];
                } else if($certification_element->module_id) {
                    $module = Module::where('id', $certification_element->module_id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();
                    if ($module){
                        $hours = $module['total_hours'];
                        $face_to_face_hours = $module['face_to_Face_hours'];
                        $teletraining_hours = $module['teletraining_hours'];
                    }
                }
                $certification_element->delete();
            }
            $certification->update([
                'total_hours' => $certification['total_hours'] - $hours,
                'face_to_face_hours' => $certification['face_to_face_hours'] - $face_to_face_hours,
                'teletraining_hours' => $certification['teletraining_hours'] - $teletraining_hours
            ]);
        }
    }
}

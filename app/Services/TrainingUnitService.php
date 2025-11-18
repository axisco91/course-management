<?php

namespace App\Services;

use App\Models\TrainingUnit;
use Carbon\Carbon;

class TrainingUnitService
{
    /**
     * Función para crear proveedor
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $face_to_face_hours = $data['exam_hours'] + $data['tutoring_hours'];
        $total_hours = $face_to_face_hours + $data['teletraining_hours'];

        $training_unit = TrainingUnit::create([
            'formative_unit' => $data['formative_unit'],
            'name' => $data['name'],
            'exam_hours' => $data['exam_hours'],
            'tutoring_hours' => $data['tutoring_hours'],
            'teletraining_hours' => $data['teletraining_hours'],
            'face_to_face_hours' => $face_to_face_hours,
            'total_hours' => $total_hours,
            'active' => $data['active'],
            'main_company_id' => $data['main_company_id'],
        ]);
        return $training_unit;
    }

    /**
     * Función para editar proveedor
     */
    public function update(TrainingUnit $training_unit, array $data) {
        $face_to_face_hours = $data['exam_hours'] + $data['tutoring_hours'];
        $total_hours = $face_to_face_hours + $data['teletraining_hours'];
        if ($training_unit){
            if ($total_hours != $training_unit->total_hours){
                $modules = $training_unit->modules()->get();
                $exam_difference = $data['exam_hours'] - $training_unit->exam_hours;
                $tutoring_difference = $data['tutoring_hours'] - $training_unit->tutoring_hours;
                $teletraining_difference = $data['teletraining_hours'] - $training_unit->teletraining_hours;
                foreach ($modules as $module){
                    $module->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
                }
                $certifications = $training_unit->certifications()->get();
                foreach ($certifications as $certification){
                    $certification->updateHours($exam_difference, $tutoring_difference, $teletraining_difference);
                }
            }
            $training_unit->update([
                'formative_unit' => $data['formative_unit'],
                'name' => $data['name'],
                'exam_hours' => $data['exam_hours'],
                'tutoring_hours' => $data['tutoring_hours'],
                'teletraining_hours' => $data['teletraining_hours'],
                'face_to_face_hours' => $face_to_face_hours,
                'total_hours' => $total_hours,
                'active' => $data['active'],
            ]);
        }
        return $training_unit;
    }
}

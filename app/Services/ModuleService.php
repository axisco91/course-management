<?php

namespace App\Services;

use App\Models\Module;

class ModuleService
{
    /**
     * Función para crear un modulo
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $face_to_face_hours = $data['exam_hours'] + $data['tutoring_hours'];
        $total_hours = $face_to_face_hours + $data['teletraining_hours'];
        return Module::create([
            'formative_module' => $data['formative_module'],
            'name' => $data['name'],
            'exam_hours' => $data['exam_hours'],
            'tutoring_hours' => $data['tutoring_hours'],
            'face_to_face_hours' => $face_to_face_hours,
            'teletraining_hours' => $data['teletraining_hours'],
            'total_hours' => $total_hours,
            'active' => $data['active'],
        ]);
    }

    /**
     * Función para editar un examen tutoría
     */
    public function update(Module $module, array $data) {
        if ($module){
            $face_to_face_hours = $data['exam_hours'] + $data['tutoring_hours'];
            $total_hours = $face_to_face_hours + $data['teletraining_hours'];
            if ($total_hours != $module->total_hours){

            }
            $module->update([
                'formative_module' => $data['formative_module'],
                'name' => $data['name'],
                'exam_hours' => $data['exam_hours'],
                'tutoring_hours' => $data['tutoring_hours'],
                'face_to_face_hours' => $face_to_face_hours,
                'teletraining_hours' => $data['teletraining_hours'],
                'total_hours' => $total_hours,
                'active' => $data['active'],
            ]);

        }
        return $module;
    }
}

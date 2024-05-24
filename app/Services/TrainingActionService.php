<?php

namespace App\Services;

use App\Models\TrainingAction;

class TrainingActionService
{
    /**
     * Función para crear una acción formativa
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $training = TrainingAction::orderBy('id', 'desc')->first();
        $id = $training['id']+1;
        if ($id < 10) {
            $formativeAction = '00'.$id;
        }
        else if ($id < 100) {
            $formativeAction = '0'.$id;
        } else {
            $formativeAction = $id;
        }
        $trainingAction = TrainingAction::create([
            'formative_action' => $formativeAction,
            'name' => $data['name'],
            'action_type_id' => $data['action_type_id'],
            'professional_family_id' => $data['professional_family_id']  != -1 ? $data['professional_family_id'] : null,
            'professional_area_id' => $data['professional_area_id'] != -1 ? $data['professional_area_id'] : null,
            'modality_id' => $data['modality_id'] != -1 ? $data['modality_id'] : null,
            'training_action_level_id' => $data['training_action_level_id'],
            'training_action_group_id' => $data['training_action_group_id'] != -1 ? $data['training_action_group_id'] : null,
            'tutoring_id' => $data['tutoring_id'] != -1 ? $data['tutoring_id'] : null,
            'face_to_face_hours' => $data['face_to_face_hours'] ? $data['face_to_face_hours'] : 0,
            'teletraining_hours' => $data['teletraining_hours'] ? $data['teletraining_hours'] : 0,
            'total_hours' => $data['total_hours'] ? $data['total_hours'] : 0,
            'price' => $data['price'],
            'objectives' => isset($data['objectives']) ? $data['objectives'] : '',
            'content' => isset($data['content']) ? $data['content'] : '',
            'user' => isset($data['user']) ? $data['user'] : null,
            'password' => isset($data['password']) ? $data['password'] : null,
            'web_platform_id' => isset($data['web_platform_id']) ? $data['web_platform_id'] : null,
            'observations' => $data['observations'],
            'number_activities' => $data['number_activities'] ? $data['number_activities'] : 0,
            'number_units' => $data['number_units'] ? $data['number_units'] : 0,
            'provider_id' => $data['provider_id'],
            'active' => $data['active'],
            'specialty' => $data['specialty'],
            'in_catalog' => $data['in_catalog'],
            'course_origin_id' => $data['course_origin_id'] != -1 ? $data['course_origin_id'] : null,
            'code' => $data['code'] ? $data['code'] : null,
        ]);

        return $trainingAction;
    }

    /**
     * Función para editar una acción formativa
     */
    public function update(TrainingAction $trainingAction, array $data) {
        $trainingAction->update([
            'name' => $data['name'],
            'action_type_id' => $data['action_type_id'],
            'professional_family_id' => $data['professional_family_id']  != -1 ? $data['professional_family_id'] : null,
            'professional_area_id' => $data['professional_area_id'] != -1 ? $data['professional_area_id'] : null,
            'modality_id' => $data['modality_id'],
            'training_action_level_id' => $data['training_action_level_id'],
            'training_action_group_id' => $data['training_action_group_id'] != -1 ? $data['training_action_group_id'] : null,
            'tutoring_id' => $data['tutoring_id'],
            'face_to_face_hours' => $data['face_to_face_hours'] ? $data['face_to_face_hours'] : 0,
            'teletraining_hours' => $data['teletraining_hours'] ? $data['teletraining_hours'] : 0,
            'total_hours' => $data['total_hours'] ? $data['total_hours'] : 0,
            'price' => $data['price'],
            'objectives' => $data['objectives'],
            'content' => $data['content'],
            'user' => $data['user'],
            'password' => $data['password'],
            'web_platform_id' => $data['web_platform_id'],
            'observations' => $data['observations'],
            'number_activities' => $data['number_activities'] ? $data['number_activities'] : 0,
            'number_units' => $data['number_units'] ? $data['number_units'] : 0,
            'provider_id' => $data['provider_id'],
            'course_origin_id' => $data['course_origin_id'] != -1 ? $data['course_origin_id'] : null,
        ]);
        $trainingAction->update([
            'active' => $data['active']
        ]);
        $trainingAction->update([
            'specialty' => $data['specialty']
        ]);
        $trainingAction->update([
            'in_catalog' => $data['in_catalog']
        ]);
        return $trainingAction;
    }
}

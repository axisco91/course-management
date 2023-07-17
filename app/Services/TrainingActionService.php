<?php

namespace App\Services;

use App\Models\TrainingAction;

class TrainingActionService
{
    public function create(array $data)
    {
        $training = TrainingAction::orderBy('id', 'desc')->first();
        $id = $training['id']+1;
        if ($id < 10) {
            $formative_action = '00'.$id;
        }
        else if ($id < 100) {
            $formative_action = '0'.$id;
        } else {
            $formative_action = $id;
        }
        $trainingAction = TrainingAction::create([
            'formative_action' => $formative_action,
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
        ]);

        return $trainingAction;
    }
}

<?php

namespace App\Services;

use App\Helpers\SettingsHelper;
use App\Models\TrainingAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrainingActionService
{
    /**
     * Función para crear una acción formativa
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $mainCompanyId = (int) ($data['main_company_id'] ?? 0);
        if ($mainCompanyId <= 0) {
            throw ValidationException::withMessages([
                'main_company_id' => 'main_company_id es obligatorio'
            ]);
        }

        // Leer setting (devuelve '1' o '0' o null)
        $automatic = SettingsHelper::getSetting('automatic_formative', $mainCompanyId);
        $automatic = (int) ($automatic ?? 0);

        // Decidir formative_action
        if ($automatic === 1) {
            // ⚠️ importante: por concurrencia, mejor transacción
            $formativeAction = DB::transaction(function () use ($mainCompanyId) {

                // Calcula el max por empresa (formative_action numerico en string "001")
                $max = DB::table('training_actions')
                    ->where('main_company_id', $mainCompanyId)
                    ->selectRaw('MAX(CAST(formative_action AS UNSIGNED)) as max_value')
                    ->value('max_value');

                $next = ((int) $max) + 1;
                return str_pad((string) $next, 3, '0', STR_PAD_LEFT);
            });
        } else {
            // Del front
            $formativeAction = $data['formative_action'] ?? null;

            if (!$formativeAction) {
                throw ValidationException::withMessages([
                    'formative_action' => 'formative_action es obligatorio cuando automatic_formative = 0'
                ]);
            }

            // Normalizar/validar (ejemplo: forzar 3 dígitos si viene "1")
            // Si NO quieres esto, quítalo y valida solo con regex.
            if (ctype_digit((string)$formativeAction)) {
                $formativeAction = str_pad((string) ((int)$formativeAction), 3, '0', STR_PAD_LEFT);
            }

            // Validación de formato: exactamente 3 dígitos (001..999)
            if (!preg_match('/^\d{3}$/', $formativeAction)) {
                throw ValidationException::withMessages([
                    'formative_action' => 'formative_action debe tener 3 dígitos (ej: 001)'
                ]);
            }

            // Validar unicidad por empresa
            $exists = TrainingAction::where('main_company_id', $mainCompanyId)
                ->where('formative_action', $formativeAction)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'formative_action' => 'Ese formative_action ya existe en esta empresa'
                ]);
            }
        }

        // Crear registro
        $trainingAction = TrainingAction::create([
            'formative_action' => $formativeAction,
            'name' => $data['name'],
            'action_type_id' => $data['action_type_id'],
            'professional_family_id' => ($data['professional_family_id'] ?? -1) != -1 ? $data['professional_family_id'] : null,
            'professional_area_id' => ($data['professional_area_id'] ?? -1) != -1 ? $data['professional_area_id'] : null,
            'modality_id' => ($data['modality_id'] ?? -1) != -1 ? $data['modality_id'] : null,
            'training_action_level_id' => $data['training_action_level_id'],
            'training_action_group_id' => ($data['training_action_group_id'] ?? -1) != -1 ? $data['training_action_group_id'] : null,
            'tutoring_id' => ($data['tutoring_id'] ?? -1) != -1 ? $data['tutoring_id'] : null,
            'face_to_face_hours' => $data['face_to_face_hours'] ?? 0,
            'teletraining_hours' => $data['teletraining_hours'] ?? 0,
            'total_hours' => $data['total_hours'] ?? 0,
            'price' => $data['price'],
            'objectives' => $data['objectives'] ?? '',
            'content' => $data['content'] ?? '',
            'user' => $data['user'] ?? null,
            'password' => $data['password'] ?? null,
            'web_platform_id' => $data['web_platform_id'] ?? null,
            'observations' => $data['observations'] ?? null,
            'number_activities' => $data['number_activities'] ?? 0,
            'number_units' => $data['number_units'] ?? 0,
            'provider_id' => $data['provider_id'],
            'active' => $data['active'],
            'specialty' => $data['specialty'],
            'in_catalog' => $data['in_catalog'],
            'course_origin_id' => ($data['course_origin_id'] ?? -1) != -1 ? $data['course_origin_id'] : null,
            'code' => $data['code'] ?? null,
            'training_tutor' => $data['training_tutor'] ?? null,
            'training_tutor_dni' => $data['training_tutor_dni'] ?? null,
            'main_company_id' => $mainCompanyId,
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
            'face_to_face_hours' => $data['face_to_face_hours'] ?? 0,
            'teletraining_hours' => $data['teletraining_hours'] ?? 0,
            'total_hours' => $data['total_hours'] ?? 0,
            'price' => $data['price'],
            'objectives' => $data['objectives'],
            'content' => $data['content'],
            'user' => $data['user'],
            'password' => $data['password'],
            'web_platform_id' => $data['web_platform_id'],
            'observations' => $data['observations'],
            'number_activities' => $data['number_activities'] ?? 0,
            'number_units' => $data['number_units'] ?? 0,
            'provider_id' => $data['provider_id'],
            'course_origin_id' => $data['course_origin_id'] != -1 ? $data['course_origin_id'] : null,
            'code' => $data['code'] ?? null,
            'training_tutor' => $data['training_tutor'] ?? null,
            'training_tutor_dni' => $data['training_tutor_dni'] ?? null,
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

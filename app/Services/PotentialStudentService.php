<?php

namespace App\Services;

use App\Models\PotentialStudent;
use Carbon\Carbon;

class PotentialStudentService
{
    /**
     * Función para crear una empresa potencial
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return PotentialStudent::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_name' => $data['company_name'],
            'date_of_birth' => $data['date_of_birth'] ? Carbon::parse($data['date_of_birth']) : null,
            'level_study_id' => $data['level_study_id'],
            'disabled' => $data['disabled'],
            'social_security_number' => $data['social_security_number'],
            'professional_category_id' => $data['professional_category_id'],
            'direction' => $data['direction'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'training_action_id' => $data['training_action_id'],
            'professional_family_id' => $data['professional_family_id'],
            'professional_area_id' => $data['professional_area_id'],
            'converted' => 0,
            'comment' => $data['comment'],
            'main_company_id' => $data['main_company_id'],
        ]);
    }

    /**
     * Función para editar una empresa potencial
     */
    public function converted(PotentialStudent $potentialStudent) {
        $potentialStudent->update([
            'converted' => 1
        ]);

        return $potentialStudent;
    }
}

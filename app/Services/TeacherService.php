<?php

namespace App\Services;

use App\Models\Teacher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


class TeacherService
{
    /**
     * Función para crear un docente
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $teacher = Teacher::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'user' => $data['user'],
            'password' => $data['password'],
            'observations' => $data['observations'],
            'iban' => $data['iban'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active'],
            'main_company_id' => $data['main_company_id'],
        ]);
        $teacher->teacherAreas()->sync($data['teacher_areas']);
        return $teacher;
    }

    /**
     * Función para editar un docente
     */
    public function update(Teacher $teacher, array $data) {
        $teacher->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'user' => $data['user'],
            'password' => $data['password'],
            'observations' => $data['observations'],
            'iban' => $data['iban'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active']
        ]);

        if (isset($data['teacher_areas']) && is_array($data['teacher_areas'])) {
            $teacher->teacherAreas()->sync($data['teacher_areas']);
        } else {
            Log::error("teacher_areas is not set or not an array", ['teacher_areas' => $data['teacher_areas']]);
        }
        return $teacher;
    }
}

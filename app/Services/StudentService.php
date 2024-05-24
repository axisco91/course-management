<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Carbon;

class StudentService
{
    /**
     * Función para crear un alumno
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Student::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
            'user' => $data['user'],
            'password' => $data['password'],
            'disabled' => $data['disabled'],
            'date_of_birth' => $data['date_of_birth'] ? Carbon::createFromFormat('d-m-Y', $data['date_of_birth'])->format('Y-m-d') : null,
            'level_study_id' => $data['level_study_id'],
            'social_security_number' => $data['social_security_number'] ? $data['social_security_number'] : null,
            'c_quote' => $data['c_quote'] ? $data['c_quote'] : null,
            'quote_group_id' => $data['quote_group_id'] ? $data['quote_group_id'] : null,
            'professional_category_id' => $data['professional_category_id'] ? $data['professional_category_id'] : null,
            'annual_gross_salary' => $data['annual_gross_salary'] ? $data['annual_gross_salary'] : null,
            'annual_hours' => $data['annual_hours'] ? $data['annual_hours'] : null,
            'hourly_cost_worker_gross' => $data['hourly_cost_worker_gross'] ? $data['hourly_cost_worker_gross'] : null,
            'direction' => $data['direction'] ? $data['direction'] : null,
            'post_code' => $data['post_code'] ? $data['post_code'] : null,
            'province_id' => $data['province_id'] ? $data['province_id'] : null,
            'population' => $data['population'] ? $data['population'] : null,
            'observation' => $data['observation'] ? $data['observation'] : null,
            'iban' => $data['iban'] ? $data['iban'] : null,
            'active' => $data['active'],
            'nationality' => $data['nationality'] ? $data['nationality'] : null,
            'legal_guardian_name' => $data['legal_guardian_name'] ? $data['legal_guardian_name'] : null,
            'legal_guardian_dni' => $data['legal_guardian_dni'] ? $data['legal_guardian_dni'] : null,
            'population_code' => $data['population_code'] ? $data['population_code'] : null,
            'nationality_code' => $data['nationality_code'] ? $data['nationality_code'] : null

        ]);
    }

    /**
     * Función para editar un alumno
     */
    public function update(Student $student, array $data) {
        $student->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
            'user' => $data['user'],
            'password' => $data['password'],
            'date_of_birth' => $data['date_of_birth'] ? Carbon::createFromFormat('d-m-Y', $data['date_of_birth'])->format('Y-m-d') : null,
            'level_study_id' => $data['level_study_id'],
            'social_security_number' => $data['social_security_number'] ? $data['social_security_number'] : null,
            'c_quote' => $data['c_quote'] ? $data['c_quote'] : null,
            'quote_group_id' => $data['quote_group_id'] ? $data['quote_group_id'] : null,
            'professional_category_id' => $data['professional_category_id'] ? $data['professional_category_id'] : null,
            'annual_gross_salary' => $data['annual_gross_salary'] ? $data['annual_gross_salary'] : null,
            'annual_hours' => $data['annual_hours'] ? $data['annual_hours'] : null,
            'hourly_cost_worker_gross' => $data['hourly_cost_worker_gross'] ? $data['hourly_cost_worker_gross'] : null,
            'direction' => $data['direction'] ? $data['direction'] : null,
            'post_code' => $data['post_code'] ? $data['post_code'] : null,
            'province_id' => $data['province_id'] ? $data['province_id'] : null,
            'population' => $data['population'] ? $data['population'] : null,
            'observation' => $data['observation'] ? $data['observation'] : null,
            'iban' => $data['iban'] ? $data['iban'] : null,
            'disabled' => $data['disabled'],
            'active' => $data['active'],
            'nationality' => $data['nationality'] ? $data['nationality'] : null,
            'legal_guardian_name' => $data['legal_guardian_name'] ? $data['legal_guardian_name'] : null,
            'legal_guardian_dni' => $data['legal_guardian_dni'] ? $data['legal_guardian_dni'] : null,
            'population_code' => $data['population_code'] ? $data['population_code'] : null,
            'nationality_code' => $data['nationality_code'] ? $data['nationality_code'] : null
        ]);
        return $student;
    }
}

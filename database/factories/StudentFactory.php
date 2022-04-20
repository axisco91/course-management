<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'surname' => $this->faker->name,
			'dni' => $this->faker->name,
			'telephone' => $this->faker->name,
			'email' => $this->faker->name,
			'company_id' => $this->faker->name,
			'user' => $this->faker->name,
			'date_of_birth' => $this->faker->name,
			'level_study_id' => $this->faker->name,
			'disabled' => $this->faker->name,
			'social_security_number' => $this->faker->name,
			'c_quote' => $this->faker->name,
			'quote_group' => $this->faker->name,
			'professional_category_id' => $this->faker->name,
			'annual_gross_salary' => $this->faker->name,
			'annual_hours' => $this->faker->name,
			'hourly_cost_worker_gross' => $this->faker->name,
			'direction' => $this->faker->name,
			'post_code' => $this->faker->name,
			'population_id' => $this->faker->name,
			'province_id' => $this->faker->name,
			'population' => $this->faker->name,
			'observation' => $this->faker->name,
			'iban' => $this->faker->name,
        ];
    }
}

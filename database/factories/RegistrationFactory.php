<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition()
    {
        return [
			'course_id' => $this->faker->name,
			'company_id' => $this->faker->name,
			'student_id' => $this->faker->name,
			'tracing_id' => $this->faker->name,
			'chore_id' => $this->faker->name,
			'price' => $this->faker->name,
        ];
    }
}

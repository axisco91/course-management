<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'surname' => $this->faker->name,
			'dni' => $this->faker->name,
			'email' => $this->faker->name,
			'telephone' => $this->faker->name,
			'user' => $this->faker->name,
			'observations' => $this->faker->name,
			'iban' => $this->faker->name,
        ];
    }
}

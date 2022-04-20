<?php

namespace Database\Factories;

use App\Models\Profitability;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProfitabilityFactory extends Factory
{
    protected $model = Profitability::class;

    public function definition()
    {
        return [
			'course_id' => $this->faker->name,
			'company_id' => $this->faker->name,
			'student_id' => $this->faker->name,
			'price' => $this->faker->name,
			'license' => $this->faker->name,
			'teacher' => $this->faker->name,
			'management' => $this->faker->name,
			'nebrija_title' => $this->faker->name,
			'discount' => $this->faker->name,
			'collaborator_commission' => $this->faker->name,
			'advisor_commission' => $this->faker->name,
			'total' => $this->faker->name,
			'benefits' => $this->faker->name,
			'observations' => $this->faker->name,
        ];
    }
}

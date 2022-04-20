<?php

namespace Database\Factories;

use App\Models\CourseType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseTypeFactory extends Factory
{
    protected $model = CourseType::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

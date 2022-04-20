<?php

namespace Database\Factories;

use App\Models\CourseStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseStatusFactory extends Factory
{
    protected $model = CourseStatus::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

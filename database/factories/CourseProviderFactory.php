<?php

namespace Database\Factories;

use App\Models\CourseProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseProviderFactory extends Factory
{
    protected $model = CourseProvider::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

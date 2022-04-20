<?php

namespace Database\Factories;

use App\Models\TeacherArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeacherAreaFactory extends Factory
{
    protected $model = TeacherArea::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

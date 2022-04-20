<?php

namespace Database\Factories;

use App\Models\AreasTeacherArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AreasTeacherAreaFactory extends Factory
{
    protected $model = AreasTeacherArea::class;

    public function definition()
    {
        return [
			'teacher_id' => $this->faker->name,
			'teacher_area_id' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\LevelStudy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LevelStudyFactory extends Factory
{
    protected $model = LevelStudy::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

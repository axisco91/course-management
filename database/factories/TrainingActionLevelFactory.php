<?php

namespace Database\Factories;

use App\Models\TrainingActionLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrainingActionLevelFactory extends Factory
{
    protected $model = TrainingActionLevel::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

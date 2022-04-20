<?php

namespace Database\Factories;

use App\Models\TrainingActionGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrainingActionGroupFactory extends Factory
{
    protected $model = TrainingActionGroup::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

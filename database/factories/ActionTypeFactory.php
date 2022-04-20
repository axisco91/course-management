<?php

namespace Database\Factories;

use App\Models\ActionType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ActionTypeFactory extends Factory
{
    protected $model = ActionType::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

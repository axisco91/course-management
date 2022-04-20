<?php

namespace Database\Factories;

use App\Models\Center;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CenterFactory extends Factory
{
    protected $model = Center::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'address' => $this->faker->name,
			'email' => $this->faker->name,
			'telephone' => $this->faker->name,
        ];
    }
}

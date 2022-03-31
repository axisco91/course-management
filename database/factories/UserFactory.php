<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'surname' => $this->faker->name,
			'username' => $this->faker->name,
			'email' => $this->faker->name,
        ];
    }
}

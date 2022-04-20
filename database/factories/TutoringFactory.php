<?php

namespace Database\Factories;

use App\Models\Tutoring;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TutoringFactory extends Factory
{
    protected $model = Tutoring::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

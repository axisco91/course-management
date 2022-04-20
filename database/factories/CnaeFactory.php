<?php

namespace Database\Factories;

use App\Models\Cnae;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CnaeFactory extends Factory
{
    protected $model = Cnae::class;

    public function definition()
    {
        return [
			'cnae' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\ProfessionalArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProfessionalAreaFactory extends Factory
{
    protected $model = ProfessionalArea::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

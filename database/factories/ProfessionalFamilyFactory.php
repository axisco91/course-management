<?php

namespace Database\Factories;

use App\Models\ProfessionalFamily;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProfessionalFamilyFactory extends Factory
{
    protected $model = ProfessionalFamily::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

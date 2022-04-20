<?php

namespace Database\Factories;

use App\Models\ProfessionalCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProfessionalCategoryFactory extends Factory
{
    protected $model = ProfessionalCategory::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

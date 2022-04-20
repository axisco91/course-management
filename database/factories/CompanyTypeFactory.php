<?php

namespace Database\Factories;

use App\Models\CompanyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyTypeFactory extends Factory
{
    protected $model = CompanyType::class;

    public function definition()
    {
        return [
			'type' => $this->faker->name,
        ];
    }
}

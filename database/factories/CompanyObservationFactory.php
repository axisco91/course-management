<?php

namespace Database\Factories;

use App\Models\CompanyObservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyObservationFactory extends Factory
{
    protected $model = CompanyObservation::class;

    public function definition()
    {
        return [
			'company_id' => $this->faker->name,
			'observation' => $this->faker->name,
        ];
    }
}

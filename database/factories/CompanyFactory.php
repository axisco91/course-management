<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'nif' => $this->faker->name,
			'type_id' => $this->faker->name,
			'activity_id' => $this->faker->name,
			'email' => $this->faker->name,
			'telephone' => $this->faker->name,
			'legal_representative' => $this->faker->name,
			'dni_legal_representative' => $this->faker->name,
			'quote' => $this->faker->name,
			'cnae_id' => $this->faker->name,
			'average_template' => $this->faker->name,
			'iban' => $this->faker->name,
			'sepa' => $this->faker->name,
			'b2b' => $this->faker->name,
			'address' => $this->faker->name,
			'post_code' => $this->faker->name,
			'population_id' => $this->faker->name,
			'province_id' => $this->faker->name,
			'population' => $this->faker->name,
			'active' => $this->faker->name,
			'available_credit' => $this->faker->name,
			'consumed_credit' => $this->faker->name,
			'remaining_credit' => $this->faker->name,
			'advisor_id' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Advisor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdvisorFactory extends Factory
{
    protected $model = Advisor::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'company_id' => $this->faker->name,
			'irpf' => $this->faker->name,
			'commission' => $this->faker->name,
			'contact_1' => $this->faker->name,
			'contact_2' => $this->faker->name,
			'contact_3' => $this->faker->name,
        ];
    }
}

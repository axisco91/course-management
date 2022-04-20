<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProviderFactory extends Factory
{
    protected $model = Provider::class;

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

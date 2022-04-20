<?php

namespace Database\Factories;

use App\Models\BillingType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BillingTypeFactory extends Factory
{
    protected $model = BillingType::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

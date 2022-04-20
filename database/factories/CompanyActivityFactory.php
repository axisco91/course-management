<?php

namespace Database\Factories;

use App\Models\CompanyActivity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyActivityFactory extends Factory
{
    protected $model = CompanyActivity::class;

    public function definition()
    {
        return [
			'activity' => $this->faker->name,
        ];
    }
}

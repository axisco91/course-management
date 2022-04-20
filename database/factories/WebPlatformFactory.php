<?php

namespace Database\Factories;

use App\Models\WebPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WebPlatformFactory extends Factory
{
    protected $model = WebPlatform::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'url' => $this->faker->name,
        ];
    }
}

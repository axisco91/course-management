<?php

namespace Database\Factories;

use App\Models\Modality;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModalityFactory extends Factory
{
    protected $model = Modality::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Bonus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BonusFactory extends Factory
{
    protected $model = Bonus::class;

    public function definition()
    {
        return [
			'course_id' => $this->faker->name,
			'company_id' => $this->faker->name,
			'course_status_id' => $this->faker->name,
			'number_students' => $this->faker->name,
			'billing' => $this->faker->name,
			'bonus' => $this->faker->name,
			'total_training_activity' => $this->faker->name,
			'organization_expenses' => $this->faker->name,
			'only_organizing_entity' => $this->faker->name,
			'average_template' => $this->faker->name,
			'salary_cost' => $this->faker->name,
			'payment_id' => $this->faker->name,
			'start_communication_date' => $this->faker->name,
			'close_communication_date' => $this->faker->name,
			'invoiced' => $this->faker->name,
			'invoice_number' => $this->faker->name,
			'invoice_date' => $this->faker->name,
			'collection_date' => $this->faker->name,
			'status_bonus' => $this->faker->name,
			'date' => $this->faker->name,
			'company_bonus' => $this->faker->name,
			'observations' => $this->faker->name,
        ];
    }
}

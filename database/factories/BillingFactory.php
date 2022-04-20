<?php

namespace Database\Factories;

use App\Models\Billing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BillingFactory extends Factory
{
    protected $model = Billing::class;

    public function definition()
    {
        return [
			'course_id' => $this->faker->name,
			'company_id' => $this->faker->name,
			'number_students' => $this->faker->name,
			'billing' => $this->faker->name,
			'bonus' => $this->faker->name,
			'total_training_activity' => $this->faker->name,
			'expenses' => $this->faker->name,
			'only_organizing_entity' => $this->faker->name,
			'salary_costs' => $this->faker->name,
			'payment_id' => $this->faker->name,
			'communication_start_date' => $this->faker->name,
			'comunication_end_date' => $this->faker->name,
			'invoiced' => $this->faker->name,
			'billing_number' => $this->faker->name,
			'billing_date' => $this->faker->name,
			'collection_date' => $this->faker->name,
			'bonus_status' => $this->faker->name,
			'company_bonus' => $this->faker->name,
			'observation' => $this->faker->name,
        ];
    }
}

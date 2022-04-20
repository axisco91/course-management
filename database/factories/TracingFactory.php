<?php

namespace Database\Factories;

use App\Models\Tracing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TracingFactory extends Factory
{
    protected $model = Tracing::class;

    public function definition()
    {
        return [
			'course_id' => $this->faker->name,
			'company_id' => $this->faker->name,
			'student_id' => $this->faker->name,
			'performed_activities' => $this->faker->name,
			'performed_hours' => $this->faker->name,
			'performed_units' => $this->faker->name,
			'follow_up_date' => $this->faker->name,
			'final_test' => $this->faker->name,
			'questionnaire' => $this->faker->name,
			'welcome_message' => $this->faker->name,
			'quarter_message' => $this->faker->name,
			'half_message' => $this->faker->name,
			'three_quarters_message' => $this->faker->name,
			'final_message' => $this->faker->name,
			'observation' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'training_action_id' => $this->faker->name,
			'group' => $this->faker->name,
			'course_type_id' => $this->faker->name,
			'teacher_id' => $this->faker->name,
			'nebrija' => $this->faker->name,
			'beginning' => $this->faker->name,
			'end' => $this->faker->name,
			'morning_schedule' => $this->faker->name,
			'afternoon_schedule' => $this->faker->name,
			'monday' => $this->faker->name,
			'tuesday' => $this->faker->name,
			'wednesday' => $this->faker->name,
			'thursday' => $this->faker->name,
			'friday' => $this->faker->name,
			'saturday' => $this->faker->name,
			'sunday' => $this->faker->name,
			'formation_center_id' => $this->faker->name,
			'delivery_center_id' => $this->faker->name,
			'outsourced' => $this->faker->name,
			'course_observation' => $this->faker->name,
			'reactivated' => $this->faker->name,
			'welcome_date' => $this->faker->name,
			'quarter_date' => $this->faker->name,
			'half_date' => $this->faker->name,
			'three_quarters_date' => $this->faker->name,
			'final_date' => $this->faker->name,
			'course_status_id' => $this->faker->name,
        ];
    }
}

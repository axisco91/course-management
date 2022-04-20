<?php

namespace Database\Factories;

use App\Models\TrainingAction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrainingActionFactory extends Factory
{
    protected $model = TrainingAction::class;

    public function definition()
    {
        return [
			'formative_action' => $this->faker->name,
			'name' => $this->faker->name,
			'teacher_id' => $this->faker->name,
			'action_type_id' => $this->faker->name,
			'professional_family_id' => $this->faker->name,
			'professional_area_id' => $this->faker->name,
			'modality_id' => $this->faker->name,
			'training_action_level_id' => $this->faker->name,
			'training_action_group_id' => $this->faker->name,
			'tutoring_id' => $this->faker->name,
			'course_z' => $this->faker->name,
			'course_avz' => $this->faker->name,
			'active' => $this->faker->name,
			'in_catalog' => $this->faker->name,
			'face_to_face_hours' => $this->faker->name,
			'teletraining_hours' => $this->faker->name,
			'total_hours' => $this->faker->name,
			'price' => $this->faker->name,
			'objectives' => $this->faker->name,
			'content' => $this->faker->name,
			'user' => $this->faker->name,
			'web_platform_id' => $this->faker->name,
			'observations' => $this->faker->name,
			'number_activities' => $this->faker->name,
			'number_units' => $this->faker->name,
			'course_provider_id' => $this->faker->name,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Chore;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChoreFactory extends Factory
{
    protected $model = Chore::class;

    public function definition()
    {
        return [
			'course_id' => $this->faker->name,
			'company_id' => $this->faker->name,
			'student_id' => $this->faker->name,
			'membership_tab_status' => $this->faker->name,
			'membership_tab_date' => $this->faker->name,
			'economic_proposal_status' => $this->faker->name,
			'economic_proposal_date' => $this->faker->name,
			'student_tab_status' => $this->faker->name,
			'student_tab_date' => $this->faker->name,
			'welcome_guid_status' => $this->faker->name,
			'welcome_guid_date' => $this->faker->name,
			'registration_status' => $this->faker->name,
			'registration_status_date' => $this->faker->name,
			'diploma_status' => $this->faker->name,
			'diploma_status_date' => $this->faker->name,
			'start_communication_status' => $this->faker->name,
			'start_communication_date' => $this->faker->name,
			'close_communication_status' => $this->faker->name,
			'close_communication_date' => $this->faker->name,
			'invoiced_status' => $this->faker->name,
			'invoiced_date' => $this->faker->name,
			'bonus_sent_status' => $this->faker->name,
			'bonus_sent_date' => $this->faker->name,
        ];
    }
}

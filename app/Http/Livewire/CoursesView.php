<?php

namespace App\Http\Livewire;

use App\Models\Center;
use App\Models\CourseStatus;
use App\Models\CourseType;
use App\Models\Teacher;
use App\Models\TrainingAction;
use Livewire\Component;
use App\Models\Course;

class CoursesView extends Component
{
    public $selected_id, $name, $training_action_id, $group, $course_type_id, $teacher_id, $nebrija, $beginning,
        $end, $morning_schedule, $afternoon_schedule, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday,
        $formation_center_id, $delivery_center_id, $outsourced, $course_observation, $reactivated, $welcome_date, $quarter_date,
        $half_date, $three_quarters_date, $final_date, $course_status_id,$price;
    public $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $training_actions, $course_types, $teachers, $formation_centers, $delivery_centers, $course_statuses, $registrations = null, $students = null, $tracings = null, $chores = null;

    public function render()
    {
        return view('livewire.courses.view');
    }

    public function mount($id){
        $this->training_actions = TrainingAction::where('active', 1)->get();
        $this->course_types = CourseType::all();
        $this->teachers = Teacher::where('active', 1)->get();
        $this->formation_centers = Center::all();
        $this->delivery_centers = Center::all();
        $this->course_statuses = CourseStatus::all();

        $record = Course::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->training_action_id = $record-> training_action_id;
        $this->group = $record-> group;
        $this->course_type_id = $record-> course_type_id;
        $this->teacher_id = $record-> teacher_id;
        $this->nebrija = $record-> nebrija;
        $this->beginning = $record-> beginning;
        $this->end = $record-> end;
        $this->morning_schedule = $record-> morning_schedule;
        $this->afternoon_schedule = $record-> afternoon_schedule;
        $this->monday = $record-> monday == 1 ? $record-> monday : null;
        $this->tuesday = $record-> tuesday == 1 ? $record-> tuesday : null;
        $this->wednesday = $record-> wednesday == 1 ? $record-> wednesday : null;
        $this->thursday = $record-> thursday == 1 ? $record-> thursday : null;
        $this->friday = $record-> friday == 1 ? $record-> friday : null;
        $this->saturday = $record-> saturday == 1 ? $record-> saturday : null;
        $this->sunday = $record-> sunday == 1 ? $record-> sunday : null;
        $this->formation_center_id = $record-> formation_center_id;
        $this->delivery_center_id = $record-> delivery_center_id;
        $this->outsourced = $record-> outsourced == 1 ? $record->outsourced : null;
        $this->course_observation = $record-> course_observation;
        $this->reactivated = $record-> reactivated;
        $this->welcome_date = $record-> welcome_date;
        $this->quarter_date = $record-> quarter_date;
        $this->half_date = $record-> half_date;
        $this->three_quarters_date = $record-> three_quarters_date;
        $this->final_date = $record-> final_date;
        $this->course_status_id = $record-> course_status_id;
        $this->price = $record->price;
    }

    public function setName(){
        $data = Course::setName($this->training_action_id, $this->selected_id);
        $this->name = $data['name'];
        $this->group = $data['group'];
        $this->price = $data['price'];
    }
}

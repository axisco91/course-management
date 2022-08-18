<?php

namespace App\Http\Livewire;

use App\Models\Center;
use App\Models\CourseStatus;
use App\Models\CourseType;
use App\Models\Teacher;
use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;

class CoursesCreate extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $training_action_id, $group, $course_type_id, $teacher_id, $nebrija, $beginning,
        $end, $morning_schedule, $afternoon_schedule, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday,
        $formation_center_id, $delivery_center_id, $outsourced, $course_observation, $reactivated, $welcome_date, $quarter_date,
        $half_date, $three_quarters_date, $final_date, $course_status_id,$price, $active;
    public $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $updateMode = false, $updateTracingMode = false;
    public $route;
    public $training_actions, $course_types, $teachers, $formation_centers, $delivery_centers, $course_statuses, $registrations = null, $students = null, $tracings = null, $chores = null;

    public function render()
    {
        return view('livewire.courses.create');
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount($id){
        $this->training_action_id = $id;
        $this->training_actions = TrainingAction::where('active', 1)->get();
        $this->course_types = CourseType::all();
        $this->teachers = Teacher::where('active', 1)->get();
        $this->formation_centers = Center::all();
        $this->delivery_centers = Center::all();
        $this->course_statuses = CourseStatus::all();
        if ($id){
            $this->setName();
        }

        $this->route = url()->previous();
    }

    public function setName(){
        $data = Course::setName($this->training_action_id, $this->selected_id);
        $this->name = $data['name'];
        $this->group = $data['group'];
        $this->price = $data['price'];
    }

    private function resetInput()
    {
        $this->name = null;
        $this->training_action_id = null;
        $this->group = null;
        $this->course_type_id = null;
        $this->teacher_id = null;
        $this->nebrija = null;
        $this->beginning = null;
        $this->end = null;
        $this->morning_schedule = null;
        $this->afternoon_schedule = null;
        $this->monday = null;
        $this->tuesday = null;
        $this->wednesday = null;
        $this->thursday = null;
        $this->friday = null;
        $this->saturday = null;
        $this->sunday = null;
        $this->formation_center_id = null;
        $this->delivery_center_id = null;
        $this->outsourced = null;
        $this->course_observation = null;
        $this->reactivated = null;
        $this->welcome_date = null;
        $this->quarter_date = null;
        $this->half_date = null;
        $this->three_quarters_date = null;
        $this->course_status_id = null;
        $this->registrations = null;
        $this->students = null;
        $this->tracings = null;
        $this->chores = null;
        $this->performed_activities = null;
        $this->performed_hours = null;
        $this->performed_units = null;
        $this->follow_up_date = null;
        $this->final_date = null;
        $this->questionnaire = null;
        $this->welcome_message = null;
        $this->quarter_message = null;
        $this->half_message = null;
        $this->three_quarters_message = null;
        $this->final_message = null;
        $this->observation = null;
        $this->price = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'training_action_id' => 'required',
            'group' => 'required',
            'course_type_id' => 'required',
            'teacher_id' => 'required',
            'beginning' => 'required',
            'end' => 'required'
        ]);

        $course_info = Course::course_data($this-> beginning, $this-> end);

        $data = [
            'name' => $this-> name,
            'training_action_id' => $this-> training_action_id,
            'group' => $this-> group,
            'course_type_id' => $this-> course_type_id,
            'teacher_id' => $this-> teacher_id,
            'nebrija' => $this-> nebrija == true ? 1 : 0,
            'beginning' => $this-> beginning,
            'end' => $this-> end,
            'morning_schedule' => $this-> morning_schedule,
            'afternoon_schedule' => $this-> afternoon_schedule,
            'monday' => $this-> monday == true ? 1 : 0,
            'tuesday' => $this-> tuesday == true ? 1 : 0,
            'wednesday' => $this-> wednesday == true ? 1 : 0,
            'thursday' => $this-> thursday == true ? 1 : 0,
            'friday' => $this-> friday == true ? 1 : 0,
            'saturday' => $this-> saturday == true ? 1 : 0,
            'sunday' => $this-> sunday == true ? 1 : 0,
            'formation_center_id' => $this-> formation_center_id,
            'delivery_center_id' => $this-> delivery_center_id,
            'outsourced' => $this-> outsourced == true ? 1 : 0,
            'course_observation' => $this-> observation,
            'reactivated' => $this-> reactivated,
            'welcome_date' => $this-> beginning,
            'quarter_date' => $course_info['quarter'],
            'half_date' => $course_info['half'],
            'three_quarters_date' => $course_info['three_quarters'],
            'final_date' => $this-> end,
            'course_status_id' => $course_info['course_status_id'],
            'price' => $this-> price,
        ];

        Course::createCourse($data);

        $this->resetInput();
        session()->flash('message', 'Course Successfully created.');
        return $this->redirect($this->route);
    }
}

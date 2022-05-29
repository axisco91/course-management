<?php

namespace App\Http\Livewire;

use App\Models\Billing;
use App\Models\Center;
use App\Models\Chore;
use App\Models\CourseStatus;
use App\Models\CourseType;
use App\Models\Profitability;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tracing;
use App\Models\TrainingAction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;

class Courses extends Component
{
    use WithPagination;

    protected $listeners = ['refreshComponent' => '$refresh'];
	protected $paginationTheme = 'bootstrap';
    public $selected_id = null, $keyWord, $name, $training_action_id, $group, $course_type_id, $teacher_id, $nebrija, $beginning,
        $end, $morning_schedule, $afternoon_schedule, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday,
        $formation_center_id, $delivery_center_id, $outsourced, $course_observation, $reactivated, $welcome_date, $quater_date,
        $half_date, $three_quarters_date, $final_date, $course_status_id,$price, $active;
    public $create_training_action_id, $create_course_type_id, $create_teacher_id, $create_formation_center_id, $create_delivery_center_id;
    public $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $updateMode = false;
    public $training_actions, $course_types, $teachers, $formation_centers, $delivery_centers, $course_statuses, $registrations = null, $students = null, $tracings = null, $chores = null;
    public $chore_id, $membership_tab_status, $economic_proposal_status,  $registration_status,
        $start_communication_date, $close_communication_date, $company_id, $company_name, $student_name;
    public $search_name;

    public function render()
    {
        if ($this->selected_id){
            $this->registrations = Student::leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
                ->where('registrations.course_id', $this->selected_id)->get();
            $this->students = Student::all();
            $this->students = $this->students->whereNotIn('id', $this->registrations->pluck('student_id'));
        }

		$keyWord = '%'.$this->keyWord .'%';
        $search_name = '%'.$this->search_name.'%';

        $courses = Course::getCourses($keyWord, $search_name);

        return view('livewire.courses.view', [
            'courses' => $courses]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){
        $this->training_actions = TrainingAction::where('active', 0)->get();
        $this->course_types = CourseType::all();
        $this->teachers = Teacher::where('active', 1)->get();
        $this->formation_centers = Center::all();
        $this->delivery_centers = Center::all();
        $this->course_statuses = CourseStatus::all();
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
		$this->quater_date = null;
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
        $this->create_training_action_id = null;
        $this->create_course_type_id = null;
        $this->create_delivery_center_id = null;
        $this->create_formation_center_id = null;
        $this->create_teacher_id = null;
    }

    public function registrations($id) {
        $this->emit('courseRegistrations', $id);
    }
}

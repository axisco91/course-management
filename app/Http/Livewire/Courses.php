<?php

namespace App\Http\Livewire;

use App\Helpers\CourseStatusHelper;
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
        $formation_center_id, $delivery_center_id, $outsourced, $course_observation, $reactivated, $welcome_date, $quarter_date,
        $half_date, $three_quarters_date, $final_date, $course_status_id,$price, $active;
    public $create_training_action_id, $create_course_type_id, $create_teacher_id, $create_formation_center_id, $create_delivery_center_id;
    public $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $updateMode = false;
    public $training_actions, $course_types, $teachers, $formation_centers, $delivery_centers, $course_statuses, $registrations = null, $students = null, $tracings = null, $chores = null;
    public $chore_id, $membership_tab_status, $economic_proposal_status,  $registration_status, $tab = 'info',
        $start_communication_date, $close_communication_date, $company_id, $company_name, $student_name;
    public $search_formative_action, $search_name, $search_student_name, $search_surname,
        $search_group, $search_type, $search_status, $search_company;

    public function render()
    {
        $courses = Course::all();

        foreach ($courses as $course){

            $course_status_id = CourseStatusHelper::updateCourseStatus($course->beginning, $course->end);

            $course->update([
                'course_status_id' => $course_status_id
            ]);
        }

        if ($this->selected_id){
            $search_name = '%'.$this->search_student_name.'%';
            $search_surname = '%'.$this->search_surname.'%';
            $this->students =Registration::getRegistrated($this->selected_id, $search_name, $search_surname);
        }

		$keyWord = '%'.$this->keyWord .'%';
        $search_formative_action = '%'.$this->search_formative_action.'%';
        $search_name = '%'.$this->search_name.'%';
        $search_group = '%'.$this->search_group.'%';
        $search_company = '%'.$this->search_company.'%';

        $courses = Course::getCourses($keyWord, $search_formative_action, $search_name, $search_group, $this->search_type, $this->search_status, $search_company);


        return view('livewire.courses.list', [
            'courses' => $courses]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){
        $this->training_actions = TrainingAction::where('active', 1)->get();
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
        $this->create_training_action_id = null;
        $this->create_course_type_id = null;
        $this->create_delivery_center_id = null;
        $this->create_formation_center_id = null;
        $this->create_teacher_id = null;
    }

    public function registrations($id) {
        $this->emit('courseRegistrations', $id);
    }

    public function general($id){
        if($id){
            $record = Course::find($id);
            $this->selected_id = $id;
            $this->name = $record->name;
            $this->training_action_id = $record->training_action_id;
            $this->group = $record->group;
            $this->course_type_id = $record->course_type_id;
            $this->teacher_id = $record->teacher_id;
            $this->nebrija = $record->nebrija;
            $this->beginning = $record->beginning;
            $this->end = $record->end;
            $this->morning_schedule = $record->morning_schedule;
            $this->afternoon_schedule = $record->afternoon_schedule;
            $this->monday = $record->monday;
            $this->tuesday = $record->tuesday;
            $this->wednesday = $record->wednesday;
            $this->thursday = $record->thursday;
            $this->friday = $record->friday;
            $this->saturday = $record->saturday;
            $this->sunday = $record->sunday;
            $this->formation_center_id = $record->formation_center_id;
            $this->delivery_center_id = $record->delivery_center_id;
            $this->outsourced = $record->outsourced;
            $this->course_observation = $record->course_observation;
            $this->reactivated = $record->reactivated;
            $this->welcome_date = $record->welcome_date;
            $this->quarter_date = $record->quarter_date;
            $this->half_date = $record->half_date;
            $this->three_quarters_date = $record->three_quarters_date;
            $this->course_status_id = $record->course_status_id;
            $this->registrations = $record->registrations;
            $this->students = $record->students;
            $this->tracings = $this->tracings;
            $this->chores = $record->chores;
            $this->performed_activities = $record->performed_activities;
            $this->performed_hours = $record->performed_hours;
            $this->performed_units = $record->performed_units;
            $this->follow_up_date = $record->follow_up_date;
            $this->final_date = $record->final_date;
            $this->questionnaire = $record->questionnaire;
            $this->welcome_message = $record->wlcome_message;
            $this->quarter_message = $record->quarter_message;
            $this->half_message = $record->half_message;
            $this->three_quarters_message = $record->three_quarters_message;
            $this->final_message = $record->final_message;
            $this->observation = $record->observation;
            $this->price = $record->price;
            $this->create_training_action_id = $record->create_training_action_id;
            $this->create_course_type_id = $record->create_course_type_id;
            $this->create_delivery_center_id = $record->create_delivery_center_id;
            $this->create_formation_center_id = $record->create_formation_center_id;
            $this->create_teacher_id = $record->create_teacher_id;
        }
    }
}

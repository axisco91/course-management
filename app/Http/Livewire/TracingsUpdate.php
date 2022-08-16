<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\Student;
use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tracing;

class TracingsUpdate extends Component
{
    public $selected_id, $course_id, $company_id, $student_id, $performed_activities, $performed_hours,
        $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message,
        $three_quarters_message, $final_message, $observation, $welcome_date, $quarter_date, $half_date, $three_quarters_date,
        $total_hours, $number_activities, $number_units, $final_date, $welcome_date_sent, $quarter_date_sent, $half_date_sent,
        $three_quarters_date_sent, $final_date_sent, $beginning, $end, $course_group;
    public $updateMode = false;
    public $courses, $companies, $students, $course_statuses;
    public $student_name, $name, $surname, $course_name;

    public function render()
    {
        return view('livewire.tracings.update');
    }

    public function mount($id){
        $this->courses = Course::all();
        $this->companies = Company::all();
        $this->students = Student::all();
        $this->course_statuses = CourseStatus::all();

        $record = Tracing::findOrFail($id);

        $this->selected_id = $id;
        $this->course_id = $record-> course_id;
        $this->company_id = $record-> company_id;
        $this->student_id = $record-> student_id;
        $this->performed_activities = $record-> performed_activities;
        $this->performed_hours = $record-> performed_hours;
        $this->performed_units = $record-> performed_units;
        $this->follow_up_date = $record-> follow_up_date;
        $this->final_test = $record-> final_test;
        $this->questionnaire = $record-> questionnaire;
        $this->welcome_message = $record-> welcome_message;
        $this->quarter_message = $record-> quarter_message;
        $this->half_message = $record-> half_message;
        $this->three_quarters_message = $record-> three_quarters_message;
        $this->final_message = $record-> final_message;
        $this->observation = $record-> observation;
        $this->welcome_date_sent = $record-> welcome_date_sent;
        $this->quarter_date_sent = $record-> quarter_date_sent;
        $this->half_date_sent = $record-> half_date_sent;
        $this->three_quarters_date_sent = $record-> three_quarters_date_sent;
        $this->final_date_sent = $record->final_date_sent;

        $course = Course::find($this->course_id);
        $this->welcome_date = $course->welcome_date;
        $this->quarter_date = $course->quarter_date;
        $this->half_date = $course->half_date;
        $this->three_quarters_date = $course->three_quarters_date;
        $this->final_date = $course->final_date;
        $training_action = TrainingAction::find($course->training_action_id);
        $this->total_hours = $training_action->total_hours;
        $this->number_activities = $training_action->number_activities;
        $this->number_units = $training_action->number_units;
        $student = Student::find($this->student_id);
        $this->name = $student->name;
        $this->surname = $student->surname;
        $this->course_name = $course->name;
        $this->beginning = $course->beginning;
        $this->course_group = $course->group;
        $this->end = $course->end;
    }

    public function update()
    {
        $this->validate([
		'course_id' => 'required',
		'company_id' => 'required',
		'student_id' => 'required',
        ]);

        if ($this->selected_id) {
			$data = [
			    'course_id' => $this-> course_id,
			    'company_id' => $this-> company_id,
			    'student_id' => $this-> student_id,
			    'performed_activities' => $this-> performed_activities,
                'performed_hours' => $this-> performed_hours,
                'performed_units' => $this-> performed_units,
                'follow_up_date' => $this-> follow_up_date,
                'final_test' => $this-> final_test,
                'questionnaire' => $this-> questionnaire,
                'welcome_message' => $this-> welcome_message,
                'quarter_message' => $this-> quarter_message,
                'half_message' => $this-> half_message,
                'three_quarters_message' => $this-> three_quarters_message,
                'final_message' => $this-> final_message,
                'observation' => $this-> observation,
                'welcome_date_sent' => $this-> welcome_date_sent,
                'quarter_date_sent' => $this-> quarter_date_sent,
                'half_date_sent' => $this-> half_date_sent,
                'three_quarters_date_sent' => $this-> three_quarters_date_sent,
                'final_date_sent' => $this-> final_date_sent
            ];
            Tracing::updateTracing($this->selected_id, $data);

            session()->flash('message', 'Seguimiento actualizada con exito.');
            return $this->redirect($this->route);
        }
    }
}

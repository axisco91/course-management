<?php

namespace App\Http\Livewire;

use App\Exports\AdvisorsExport;
use App\Exports\TracingsExport;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\Student;
use App\Models\TrainingAction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tracing;

class Tracings extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $performed_activities, $performed_hours,
        $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message,
        $three_quarters_message, $final_message, $observation, $welcome_date, $quarter_date, $half_date, $three_quarters_date,
        $total_hours, $number_activities, $number_units, $final_date, $welcome_date_sent, $quarter_date_sent, $half_date_sent,
        $three_quarters_date_sent, $final_date_sent, $beginning, $end, $course_group;
    public $updateMode = false;
    public $courses, $companies, $students, $course_statuses, $tab = 'info';
    public $course_search = -1, $company_search = -1, $student_search = -1, $status_search = -1, $student_name, $name, $surname, $course_name, $beginning_search, $end_search;

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $tracings = Tracing::getTracings($keyWord, $this->course_search, $this->company_search, $this->student_search, $this->status_search, $this->beginning_search, $this->end_search);
        return view('livewire.tracings.list', [
            'tracings' => $tracings
        ]);
    }

    public function mount(){
        $this->courses = Course::all();
        $this->companies = Company::all();
        $this->students = Student::all();
        $this->course_statuses = CourseStatus::all();
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->course_id = null;
		$this->company_id = null;
		$this->student_id = null;
		$this->performed_activities = null;
		$this->performed_hours = null;
		$this->performed_units = null;
		$this->follow_up_date = null;
		$this->final_test = null;
		$this->questionnaire = null;
		$this->welcome_message = null;
		$this->quarter_message = null;
		$this->half_message = null;
		$this->three_quarters_message = null;
		$this->final_message = null;
		$this->observation = null;
        $this->welcome_date_sent = null;
        $this->quarter_date_sent = null;
        $this->half_date_sent = null;
        $this->three_quarters_date_sent = null;
        $this->final_date_sent = null;
        $this->welcome_date = null;
        $this->quarter_date = null;
        $this->half_date = null;
        $this->three_quarters_date = null;
        $this->final_date = null;
        $this->total_hours = null;
        $this->number_activities = null;
        $this->number_units = null;
        $this->name = null;
        $this->surname = null;
        $this->course_name = null;
    }

    public function edit($id)
    {
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
        $this->beginning = Carbon::parse($course->beginning)->format('d/m/Y');
        $this->end = Carbon::parse($course->end)->format('d/m/Y');
        $this->course_group = $course->group;

        $this->updateMode = true;
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
            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
            session()->flash('message', 'Seguimiento actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function updateInfo()
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
            session()->flash('message', 'Seguimiento actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function general($id){
        $record = Tracing::findOrFail($id);

        $this->selected_id = $id;
        $this->course_id = $record-> course_id;
        $this->company_id = $record-> company_id;
        $this->student_id = $record-> student_id;
        $student = Student::find($this->student_id);
        $course = Course::find($this->course_id);

        $this->student_name = $student->name .' '. $student->surname;
        $this->course_name = $course->name;
        $this->beginning = Carbon::parse($course->beginning)->format('d/m/Y');
        $this->end = Carbon::parse($course->end)->format('d/m/Y');
        $this->course_group = $course->group;
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

        $this->welcome_date = $course->welcome_date;
        $this->quarter_date = $course->quarter_date;
        $this->half_date = $course->half_date;
        $this->three_quarters_date = $course->three_quarters_date;
        $this->final_date = $course->final_date;
        $training_action = TrainingAction::find($course->training_action_id);
        $this->total_hours = $training_action->total_hours;
        $this->number_activities = $training_action->number_activities;
        $this->number_units = $training_action->number_units;
        $this->name = $student->name;
        $this->surname = $student->surname;
    }

    public function downloadExcel(){
        $this->excelModal = false;
        return (new TracingsExport($this->course_search, $this->company_search, $this->student_search, $this->status_search, $this->beginning_search, $this->end_search))->download('seguimiento.xlsx');
    }
}

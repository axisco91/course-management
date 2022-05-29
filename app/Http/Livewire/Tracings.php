<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tracing;

class Tracings extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $updateMode = false;
    public $courses, $companies, $students;
    public $course_search = -1, $company_search = -1, $student_search = -1, $student_name, $course_name;

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $tracings = Tracing::getTracings($keyWord);
        return view('livewire.tracings.view', [
            'tracings' => $tracings
        ]);
    }

    public function mount(){
        $this->courses = Course::all();
        $this->companies = Company::all();
        $this->students = Student::all();
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
			'observation' => $this-> observation
            ];
            Tracing::updateTracing($this->selected_id, $data);
            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tracing Successfully updated.');
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
    }
}

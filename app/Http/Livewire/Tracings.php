<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tracing;

class Tracings extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $performed_activities, $performed_hours, $performed_units, $follow_up_date, $final_test, $questionnaire, $welcome_message, $quarter_message, $half_message, $three_quarters_message, $final_message, $observation;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.tracings.view', [
            'tracings' => Tracing::latest()
						->orWhere('course_id', 'LIKE', $keyWord)
						->orWhere('company_id', 'LIKE', $keyWord)
						->orWhere('student_id', 'LIKE', $keyWord)
						->orWhere('performed_activities', 'LIKE', $keyWord)
						->orWhere('performed_hours', 'LIKE', $keyWord)
						->orWhere('performed_units', 'LIKE', $keyWord)
						->orWhere('follow_up_date', 'LIKE', $keyWord)
						->orWhere('final_test', 'LIKE', $keyWord)
						->orWhere('questionnaire', 'LIKE', $keyWord)
						->orWhere('welcome_message', 'LIKE', $keyWord)
						->orWhere('quarter_message', 'LIKE', $keyWord)
						->orWhere('half_message', 'LIKE', $keyWord)
						->orWhere('three_quarters_message', 'LIKE', $keyWord)
						->orWhere('final_message', 'LIKE', $keyWord)
						->orWhere('observation', 'LIKE', $keyWord)
						->paginate(10),
        ]);
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

    public function store()
    {
        $this->validate([
		'course_id' => 'required',
		'company_id' => 'required',
		'student_id' => 'required',
        ]);

        Tracing::create([
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
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tracing Successfully created.');
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
			$record = Tracing::find($this->selected_id);
            $record->update([
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
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tracing Successfully updated.');
        }
    }
}

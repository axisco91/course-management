<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Chore;

class ChoresUpdate extends Component
{
    use WithPagination;

    public $selected_id, $course_id, $company_id, $student_id, $membership_tab_status, $membership_tab_date, $economic_proposal_status,
        $economic_proposal_date, $student_tab_status, $student_tab_date, $welcome_guid_status, $welcome_guid_date, $registration_status,
        $registration_date, $diploma_status, $diploma_status_date, $start_communication_status, $start_communication_date,
        $close_communication_status, $close_communication_date, $invoiced_status, $invoiced_date, $bonus_sent_status, $bonus_sent_date,
        $name, $surname, $course_name;
    public $updateMode = false;
    public $courses, $companies, $students, $route;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
        return view('livewire.chores.update');
    }

    public function mount($id){
        $this->courses = Course::all();
        $this->companies = Company::all();
        $this->students = Student::all();

        $record = Chore::findOrFail($id);

        $this->selected_id = $id;
        $this->course_id = $record-> course_id;
        $this->company_id = $record-> company_id;
        $this->student_id = $record-> student_id;
        $this->membership_tab_status = $record-> membership_tab_status;
        $this->membership_tab_date = $record-> membership_tab_date;
        $this->economic_proposal_status = $record-> economic_proposal_status;
        $this->economic_proposal_date = $record-> economic_proposal_date;
        $this->student_tab_status = $record-> student_tab_status;
        $this->student_tab_date = $record-> student_tab_date;
        $this->welcome_guid_status = $record-> welcome_guid_status;
        $this->welcome_guid_date = $record-> welcome_guid_date;
        $this->registration_status = $record-> registration_status;
        $this->registration_date = $record-> registration_date;
        $this->diploma_status = $record-> diploma_status;
        $this->diploma_status_date = $record-> diploma_status_date;
        $this->start_communication_status = $record-> start_communication_status;
        $this->start_communication_date = $record-> start_communication_date;
        $this->close_communication_status = $record-> close_communication_status;
        $this->close_communication_date = $record-> close_communication_date;
        $this->invoiced_status = $record-> invoiced_status;
        $this->invoiced_date = $record-> invoiced_date;
        $this->bonus_sent_status = $record-> bonus_sent_status;
        $this->bonus_sent_date = $record-> bonus_sent_date;
        $student = Student::find($this->student_id);
        $course = Course::find($this->course_id);
        $this->name = $student->name;
        $this->surname = $student->surname;
        $this->course_name = $course->name;

        $this->route = url()->previous();
    }

    private function resetInput()
    {
		$this->course_id = null;
		$this->company_id = null;
		$this->student_id = null;
		$this->membership_tab_status = null;
		$this->membership_tab_date = null;
		$this->economic_proposal_status = null;
		$this->economic_proposal_date = null;
		$this->student_tab_status = null;
		$this->student_tab_date = null;
		$this->welcome_guid_status = null;
		$this->welcome_guid_date = null;
		$this->registration_status = null;
		$this->registration_date = null;
		$this->diploma_status = null;
		$this->diploma_status_date = null;
		$this->start_communication_status = null;
		$this->start_communication_date = null;
		$this->close_communication_status = null;
		$this->close_communication_date = null;
		$this->invoiced_status = null;
		$this->invoiced_date = null;
		$this->bonus_sent_status = null;
		$this->bonus_sent_date = null;
    }

    public function update()
    {
        if ($this->selected_id) {
            $data = [
			'membership_tab_status' => $this-> membership_tab_status,
			'membership_tab_date' => $this-> membership_tab_date,
			'economic_proposal_status' => $this-> economic_proposal_status,
			'economic_proposal_date' => $this-> economic_proposal_date,
			'student_tab_status' => $this-> student_tab_status,
			'student_tab_date' => $this-> student_tab_date,
			'welcome_guid_status' => $this-> welcome_guid_status,
			'welcome_guid_date' => $this-> welcome_guid_date,
			'registration_status' => $this-> registration_status,
			'registration_date' => $this-> registration_date,
			'diploma_status' => $this-> diploma_status,
			'diploma_status_date' => $this-> diploma_status_date,
			'start_communication_status' => $this-> start_communication_status,
			'start_communication_date' => $this-> start_communication_date,
			'close_communication_status' => $this-> close_communication_status,
			'close_communication_date' => $this-> close_communication_date,
			'invoiced_status' => $this-> invoiced_status,
			'invoiced_date' => $this-> invoiced_date,
			'bonus_sent_status' => $this-> bonus_sent_status,
			'bonus_sent_date' => $this-> bonus_sent_date
            ];

            Chore::updateChore($this->selected_id, $data);

            $this->resetInput();
			session()->flash('message', 'Tarea actualizado con exito.');
            return $this->redirect($this->route);
        }
    }

    public function general($id){
        $record = Chore::findOrFail($id);

        $this->selected_id = $id;
        $this->course_id = $record-> course_id;
        $this->company_id = $record-> company_id;
        $this->student_id = $record-> student_id;

        $student = Student::find($this->student_id);
        $course = Course::find($this->course_id);

        $this->student_name = $student->name .' '. $student->surname;
        $this->course_name = $course->name;
        $this->membership_tab_status = $record-> membership_tab_status;
        $this->membership_tab_date = $record-> membership_tab_date;
        $this->economic_proposal_status = $record-> economic_proposal_status;
        $this->economic_proposal_date = $record-> economic_proposal_date;
        $this->student_tab_status = $record-> student_tab_status;
        $this->student_tab_date = $record-> student_tab_date;
        $this->welcome_guid_status = $record-> welcome_guid_status;
        $this->welcome_guid_date = $record-> welcome_guid_date;
        $this->registration_status = $record-> registration_status;
        $this->registration_date = $record-> registration_date;
        $this->diploma_status = $record-> diploma_status;
        $this->diploma_status_date = $record-> diploma_status_date;
        $this->start_communication_status = $record-> start_communication_status;
        $this->start_communication_date = $record-> start_communication_date;
        $this->close_communication_status = $record-> close_communication_status;
        $this->close_communication_date = $record-> close_communication_date;
        $this->invoiced_status = $record-> invoiced_status;
        $this->invoiced_date = $record-> invoiced_date;
        $this->bonus_sent_status = $record-> bonus_sent_status;
        $this->bonus_sent_date = $record-> bonus_sent_date;
    }
}

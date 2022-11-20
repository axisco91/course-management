<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use Livewire\Component;
use App\Models\Chore;

class ChoresUpdate extends Component
{
    public $selected_id, $course_id, $company_id, $student_id, $membership_tab_status, $membership_tab_date, $economic_proposal_status,
        $economic_proposal_date, $student_tab_status, $student_tab_date, $welcome_guid_status, $welcome_guid_date, $registration_status,
        $registration_date, $diploma_status, $diploma_status_date, $start_communication_status, $start_communication_date,
        $close_communication_status, $close_communication_date, $invoiced_status, $invoiced_date, $bonus_sent_status, $bonus_sent_date,
        $name, $surname, $course_name;
    public $courses, $companies, $students;
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
			session()->flash('message', 'Tarea actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }
}

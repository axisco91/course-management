<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Billing;

class Billings extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $number_students, $billing, $bonus, $total_training_activity, $expenses, $only_organizing_entity, $salary_costs, $payment_id, $communication_start_date, $communication_end_date, $invoiced, $billing_number, $billing_date, $collection_date, $bonus_status, $company_bonus, $observation, $is_bonus, $group;
    public $courses, $companies, $payments, $tab = 'info', $students, $search_student_name, $search_surname;
    public $updateMode = false;
    public $course_search = -1, $company_search = -1, $student_search = -1, $is_bonus_search = -1;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $billings = Billing::getBillings($keyWord, $this->course_search, $this->company_search, $this->student_search, $this->is_bonus_search);

        if ($this->selected_id){
            $search_student_name = '%'.$this->search_student_name.'%';
            $search_surname = '%'.$this->search_surname.'%';
            $this->students = Student::getBilledStudent($this->selected_id, $search_student_name, $search_surname);
        }

        return view('livewire.billings.list', [
            'billings' => $billings,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){
        $this-> courses = Course::all();
        $this-> companies = Company::all();
        $this-> payments = Payment::all();
        $this-> students = Student::all();
    }

    private function resetInput()
    {
		$this->course_id = null;
		$this->company_id = null;
		$this->number_students = null;
		$this->billing = null;
		$this->bonus = null;
		$this->total_training_activity = null;
		$this->expenses = null;
		$this->only_organizing_entity = null;
		$this->salary_costs = null;
		$this->payment_id = null;
		$this->communication_start_date = null;
		$this->communication_end_date = null;
		$this->invoiced = null;
		$this->billing_number = null;
		$this->billing_date = null;
		$this->collection_date = null;
		$this->bonus_status = null;
		$this->company_bonus = null;
		$this->observation = null;
        $this->is_bonus = null;
    }

    public function destroy($id)
    {
        if ($id) {
            Registration::eliminateBill($id);
            $value = Billing::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }

    public function general($id){
        $record = Billing::findOrFail($id);

        $this->selected_id = $id;
        $this->course_id = $record-> course_id;
        $this->company_id = $record-> company_id;
        $this->number_students = $record-> number_students;
        $this->billing = $record-> billing;
        $this->bonus = $record-> bonus;
        $this->total_training_activity = $record-> total_training_activity;
        $this->expenses = $record-> expenses;
        $this->only_organizing_entity = $record-> only_organizing_entity;
        $this->salary_costs = $record-> salary_costs;
        $this->payment_id = $record-> payment_id;
        $this->communication_start_date = $record-> communication_start_date;
        $this->communication_end_date = $record-> communication_end_date;
        $this->invoiced = $record-> invoiced;
        $this->billing_number = $record-> billing_number;
        $this->billing_date = $record-> billing_date;
        $this->collection_date = $record-> collection_date;
        $this->bonus_status = $record-> bonus_status;
        $this->company_bonus = $record-> company_bonus;
        $this->observation = $record-> observation;
        $this->is_bonus = $record-> is_bonus;

        $course = Course::find($this->course_id);
        $this->group = $course->group;
    }
}

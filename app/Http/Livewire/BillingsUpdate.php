<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Course;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Billing;

class BillingsUpdate extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $course_id, $company_id, $number_students, $billing, $bonus, $total_training_activity, $expenses, $only_organizing_entity, $salary_costs, $payment_id, $communication_start_date, $comunication_end_date, $invoiced, $billing_number, $billing_date, $collection_date, $bonus_status, $company_bonus, $observation, $is_bonus;
    public $courses, $companies, $payments, $route;

    public function render()
    {
        return view('livewire.billings.edit');
    }

    public function mount($id){
        $this-> courses = Course::all();
        $this-> companies = Company::all();
        $this-> payments = Payment::all();

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
        $this->comunication_end_date = $record-> comunication_end_date;
        $this->invoiced = $record-> invoiced;
        $this->billing_number = $record-> billing_number;
        $this->billing_date = $record-> billing_date;
        $this->collection_date = $record-> collection_date;
        $this->bonus_status = $record-> bonus_status;
        $this->company_bonus = $record-> company_bonus;
        $this->observation = $record-> observation;
        $this->is_bonus = $record-> is_bonus;


    }

    public function update()
    {
        $this->validate([
            'course_id' => 'required',
            'company_id' => 'required',
            'number_students' => 'required',
            'billing' => 'required',
            'bonus' => 'required',
            'total_training_activity' => 'required',
            'expenses' => 'required',
            'only_organizing_entity' => 'required',
            'salary_costs' => 'required',
            'invoiced' => 'required',
            'bonus_status' => 'required',
            'company_bonus' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Billing::find($this->selected_id);
            $record->update([
                'course_id' => $this-> course_id,
                'company_id' => $this-> company_id,
                'number_students' => $this-> number_students,
                'billing' => $this-> billing,
                'bonus' => $this-> bonus,
                'total_training_activity' => $this-> total_training_activity,
                'expenses' => $this-> expenses,
                'only_organizing_entity' => $this-> only_organizing_entity,
                'salary_costs' => $this-> salary_costs,
                'payment_id' => $this-> payment_id,
                'communication_start_date' => $this-> communication_start_date,
                'comunication_end_date' => $this-> comunication_end_date,
                'invoiced' => $this-> invoiced,
                'billing_number' => $this-> billing_number,
                'billing_date' => $this-> billing_date,
                'collection_date' => $this-> collection_date,
                'bonus_status' => $this-> bonus_status,
                'company_bonus' => $this-> company_bonus,
                'observation' => $this-> observation,
                'is_bonus' => $this-> is_bonus,
            ]);

            session()->flash('message', 'Billing Successfully updated.');
            return $this->redirect($this->route);
        }
    }
}

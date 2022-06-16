<?php

namespace App\Http\Livewire;

use App\Models\Chore;
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
    public $selected_id, $course_id, $company_id, $number_students, $billing, $bonus, $total_training_activity, $expenses, $only_organizing_entity, $salary_costs, $payment_id, $communication_start_date, $communication_end_date, $invoiced, $billing_number, $billing_date, $collection_date, $bonus_status, $company_bonus, $observation, $is_bonus, $group;
    public $courses, $companies, $payments, $route, $tab = 'info';

    public function render()
    {
        return view('livewire.billings.update');
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

        $this->route = url()->previous();
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

    public function update()
    {
        $this->validate([
            'course_id' => 'required',
            'company_id' => 'required',
            'number_students' => 'required',
            'billing' => 'required',
            'bonus' => 'required',
            'expenses' => 'required',
            'only_organizing_entity' => 'required',
            'salary_costs' => 'required',
            'invoiced' => 'required',
            'bonus_status' => 'required',
            'company_bonus' => 'required',
        ]);

        if ($this->is_bonus) {
            $this->expenses = Billing::calculateExpenses($this->billing);
            $this->total_training_activity = Billing::totalTrainingActivity($this->bonus, $this->expenses);
        }

        if ($this->selected_id) {
            $data = [
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
                'communication_end_date' => $this-> communication_end_date,
                'invoiced' => $this-> invoiced,
                'billing_number' => $this-> billing_number,
                'billing_date' => $this-> billing_date,
                'collection_date' => $this-> collection_date,
                'bonus_status' => $this-> bonus_status,
                'company_bonus' => $this-> company_bonus,
                'observation' => $this-> observation,
                'is_bonus' => $this-> is_bonus,
            ];

            $billing = Billing::updateBilling($this->selected_id, $data);
            Chore::billingDateChore($this->selected_id, $this->billing_date);
            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Factura Actulizado con exito.');
            return $this->redirect($this->route);
        }
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bonus;

class Bonuss extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $course_status_id, $number_students, $billing, $bonus, $total_training_activity, $organization_expenses, $only_organizing_entity, $average_template, $salary_cost, $payment_id, $start_communication_date, $close_communication_date, $invoiced, $invoice_number, $invoice_date, $collection_date, $status_bonus, $date, $company_bonus, $observations;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.bonuses.view', [
            'bonuses' => Bonus::latest()
						->orWhere('course_id', 'LIKE', $keyWord)
						->orWhere('company_id', 'LIKE', $keyWord)
						->orWhere('course_status_id', 'LIKE', $keyWord)
						->orWhere('number_students', 'LIKE', $keyWord)
						->orWhere('billing', 'LIKE', $keyWord)
						->orWhere('bonus', 'LIKE', $keyWord)
						->orWhere('total_training_activity', 'LIKE', $keyWord)
						->orWhere('organization_expenses', 'LIKE', $keyWord)
						->orWhere('only_organizing_entity', 'LIKE', $keyWord)
						->orWhere('average_template', 'LIKE', $keyWord)
						->orWhere('salary_cost', 'LIKE', $keyWord)
						->orWhere('payment_id', 'LIKE', $keyWord)
						->orWhere('start_communication_date', 'LIKE', $keyWord)
						->orWhere('close_communication_date', 'LIKE', $keyWord)
						->orWhere('invoiced', 'LIKE', $keyWord)
						->orWhere('invoice_number', 'LIKE', $keyWord)
						->orWhere('invoice_date', 'LIKE', $keyWord)
						->orWhere('collection_date', 'LIKE', $keyWord)
						->orWhere('status_bonus', 'LIKE', $keyWord)
						->orWhere('date', 'LIKE', $keyWord)
						->orWhere('company_bonus', 'LIKE', $keyWord)
						->orWhere('observations', 'LIKE', $keyWord)
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
		$this->course_status_id = null;
		$this->number_students = null;
		$this->billing = null;
		$this->bonus = null;
		$this->total_training_activity = null;
		$this->organization_expenses = null;
		$this->only_organizing_entity = null;
		$this->average_template = null;
		$this->salary_cost = null;
		$this->payment_id = null;
		$this->start_communication_date = null;
		$this->close_communication_date = null;
		$this->invoiced = null;
		$this->invoice_number = null;
		$this->invoice_date = null;
		$this->collection_date = null;
		$this->status_bonus = null;
		$this->date = null;
		$this->company_bonus = null;
		$this->observations = null;
    }

    public function store()
    {
        $this->validate([
		'course_id' => 'required',
		'company_id' => 'required',
		'course_status_id' => 'required',
		'number_students' => 'required',
		'billing' => 'required',
		'bonus' => 'required',
		'total_training_activity' => 'required',
		'organization_expenses' => 'required',
		'only_organizing_entity' => 'required',
		'average_template' => 'required',
		'salary_cost' => 'required',
		'payment_id' => 'required',
		'invoiced' => 'required',
		'status_bonus' => 'required',
		'company_bonus' => 'required',
        ]);

        Bonus::create([ 
			'course_id' => $this-> course_id,
			'company_id' => $this-> company_id,
			'course_status_id' => $this-> course_status_id,
			'number_students' => $this-> number_students,
			'billing' => $this-> billing,
			'bonus' => $this-> bonus,
			'total_training_activity' => $this-> total_training_activity,
			'organization_expenses' => $this-> organization_expenses,
			'only_organizing_entity' => $this-> only_organizing_entity,
			'average_template' => $this-> average_template,
			'salary_cost' => $this-> salary_cost,
			'payment_id' => $this-> payment_id,
			'start_communication_date' => $this-> start_communication_date,
			'close_communication_date' => $this-> close_communication_date,
			'invoiced' => $this-> invoiced,
			'invoice_number' => $this-> invoice_number,
			'invoice_date' => $this-> invoice_date,
			'collection_date' => $this-> collection_date,
			'status_bonus' => $this-> status_bonus,
			'date' => $this-> date,
			'company_bonus' => $this-> company_bonus,
			'observations' => $this-> observations
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Bonus Successfully created.');
    }

    public function edit($id)
    {
        $record = Bonus::findOrFail($id);

        $this->selected_id = $id; 
		$this->course_id = $record-> course_id;
		$this->company_id = $record-> company_id;
		$this->course_status_id = $record-> course_status_id;
		$this->number_students = $record-> number_students;
		$this->billing = $record-> billing;
		$this->bonus = $record-> bonus;
		$this->total_training_activity = $record-> total_training_activity;
		$this->organization_expenses = $record-> organization_expenses;
		$this->only_organizing_entity = $record-> only_organizing_entity;
		$this->average_template = $record-> average_template;
		$this->salary_cost = $record-> salary_cost;
		$this->payment_id = $record-> payment_id;
		$this->start_communication_date = $record-> start_communication_date;
		$this->close_communication_date = $record-> close_communication_date;
		$this->invoiced = $record-> invoiced;
		$this->invoice_number = $record-> invoice_number;
		$this->invoice_date = $record-> invoice_date;
		$this->collection_date = $record-> collection_date;
		$this->status_bonus = $record-> status_bonus;
		$this->date = $record-> date;
		$this->company_bonus = $record-> company_bonus;
		$this->observations = $record-> observations;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'course_id' => 'required',
		'company_id' => 'required',
		'course_status_id' => 'required',
		'number_students' => 'required',
		'billing' => 'required',
		'bonus' => 'required',
		'total_training_activity' => 'required',
		'organization_expenses' => 'required',
		'only_organizing_entity' => 'required',
		'average_template' => 'required',
		'salary_cost' => 'required',
		'payment_id' => 'required',
		'invoiced' => 'required',
		'status_bonus' => 'required',
		'company_bonus' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Bonus::find($this->selected_id);
            $record->update([ 
			'course_id' => $this-> course_id,
			'company_id' => $this-> company_id,
			'course_status_id' => $this-> course_status_id,
			'number_students' => $this-> number_students,
			'billing' => $this-> billing,
			'bonus' => $this-> bonus,
			'total_training_activity' => $this-> total_training_activity,
			'organization_expenses' => $this-> organization_expenses,
			'only_organizing_entity' => $this-> only_organizing_entity,
			'average_template' => $this-> average_template,
			'salary_cost' => $this-> salary_cost,
			'payment_id' => $this-> payment_id,
			'start_communication_date' => $this-> start_communication_date,
			'close_communication_date' => $this-> close_communication_date,
			'invoiced' => $this-> invoiced,
			'invoice_number' => $this-> invoice_number,
			'invoice_date' => $this-> invoice_date,
			'collection_date' => $this-> collection_date,
			'status_bonus' => $this-> status_bonus,
			'date' => $this-> date,
			'company_bonus' => $this-> company_bonus,
			'observations' => $this-> observations
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Bonus Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Bonus::where('id', $id);
            $record->delete();
        }
    }
}

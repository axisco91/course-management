<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CompanyObservation;

class CompanyObservations extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $company_id, $observation;
    public $updateMode = false;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.company-observations.view', [
            'companyObservations' => CompanyObservation::latest()
						->orWhere('company_id', 'LIKE', $keyWord)
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
		$this->company_id = null;
		$this->observation = null;
    }

    public function store()
    {
        $this->validate([
		'company_id' => 'required',
		'observation' => 'required',
        ]);

        CompanyObservation::create([
			'company_id' => $this-> company_id,
			'observation' => $this-> observation
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'CompanyObservation Successfully created.');
    }

    public function edit($id)
    {
        $record = CompanyObservation::findOrFail($id);

        $this->selected_id = $id;
		$this->company_id = $record-> company_id;
		$this->observation = $record-> observation;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'company_id' => 'required',
		'observation' => 'required',
        ]);

        if ($this->selected_id) {
			$record = CompanyObservation::find($this->selected_id);
            $record->update([
			'company_id' => $this-> company_id,
			'observation' => $this-> observation
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'CompanyObservation Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = CompanyObservation::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

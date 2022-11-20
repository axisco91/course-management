<?php

namespace App\Http\Livewire;

use App\Models\TrainingContractStatus;
use Livewire\Component;
use Livewire\WithPagination;

class TrainingContractStatuses extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $training_contract_statuses = TrainingContractStatus::getTrainingContractStatus($keyWord);
        return view('livewire.training-contract-statuses.view', [
            'training_contract_statuses' => $training_contract_statuses,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->name = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
        ]);

        $data = [
			'name' => $this-> name
        ];
        TrainingContractStatus::createTrainingContractStatus($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Estado guardado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = TrainingContractStatus::findOrFail($id);

        $this->selected_id = $id;
		$this->name = $record-> name;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'name' => $this-> name
            ];
            TrainingContractStatus::updateTrainingContractStatus($this->selected_id, $data);
            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Estado actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = TrainingContractStatus::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

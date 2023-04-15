<?php

namespace App\Http\Livewire;

use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TrainingActionLevel;

class TrainingActionLevels extends Component
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
        $training_action_levels = TrainingActionLevel::getTrainingActionLevel($keyWord);
        return view('livewire.training-action-levels.view', [
            'trainingActionLevels' => $training_action_levels,
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
        TrainingActionLevel::createTrainingActionLevel($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Nivel guardado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = TrainingActionLevel::findOrFail($id);

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
            TrainingActionLevel::updateTrainingActionLevel($this->selected_id, $data);
            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Nivel actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = TrainingActionLevel::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

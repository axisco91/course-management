<?php

namespace App\Http\Livewire;

use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TrainingActionGroup;

class TrainingActionGroups extends Component
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
        $training_action_groups = TrainingActionGroup::getTrainingActionGroups($keyWord);
        return view('livewire.training-action-groups.view', [
            'trainingActionGroups' => $training_action_groups,
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
        TrainingActionGroup::createTrainingActionGroup($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Grupo creado con exito.');
    }

    public function edit($id)
    {
        $record = TrainingActionGroup::findOrFail($id);

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
            TrainingActionGroup::updateTrainingActionGroup($this->selected_id, $data);
            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Grupo actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = TrainingActionGroup::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

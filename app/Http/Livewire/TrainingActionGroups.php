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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $training_action_groups = TrainingActionGroup::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($training_action_groups as $training_action_group){
            $training_action = TrainingAction::where('training_action_group_id', $training_action_group['id'])->first();
            if ($training_action){
                $training_action_group['used'] = true;
            } else {
                $training_action_group['used'] = false;
            }
        }
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

        TrainingActionGroup::create([
			'name' => $this-> name
        ]);

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
			$record = TrainingActionGroup::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Grupo actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = TrainingActionGroup::where('id', $id);
            $record->delete();
        }
    }
}

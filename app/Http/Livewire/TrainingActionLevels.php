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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $training_action_levels = TrainingActionLevel::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($training_action_levels as $training_action_level){
            $training_action = TrainingAction::where('training_action_level_id', $training_action_level['id'])->first();
            if ($training_action){
                $training_action_level['used'] = true;
            } else {
                $training_action_level['used'] = false;
            }
        }
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

        TrainingActionLevel::create([
			'name' => $this-> name
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Nivel guardado con exito.');
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
			$record = TrainingActionLevel::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Nivel actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = TrainingActionLevel::where('id', $id);
            $record->delete();
        }
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActionType;

class ActionTypes extends Component
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

       $actionTypes = ActionType::getActionType($keyWord);

        foreach($actionTypes as $actionType){

            $action = TrainingAction::where('action_type_id', $actionType['id'])->first();
            if ($action){
                $actionType['used'] = true;
            } else {
                $actionType['used'] = false;
            }
        }

        return view('livewire.action-types.view', [
            'actionTypes' => $actionTypes,
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

        ActionType::createActionType($this->name);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tipo creado con exito.');
    }

    public function edit($id)
    {
        $record = ActionType::findOrFail($id);

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
            ActionType::updateActionType($this->selected_id, $this->name);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tipo actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = ActionType::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modality;

class Modalities extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $modalities = Modality::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($modalities as $modality){
            $training_action = TrainingAction::where('modality_id', $modality['id'])->first();
            if ($training_action){
                $modality['used'] = true;
            } else {
                $modality['used'] = false;
            }
        }
        return view('livewire.modalities.view', [
            'modalities' => $modalities,
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

        Modality::create([
			'name' => $this-> name
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Modalidad creado con exito.');
    }

    public function edit($id)
    {
        $record = Modality::findOrFail($id);

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
			$record = Modality::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Modalidad actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Modality::where('id', $id);
            $record->delete();
        }
    }
}

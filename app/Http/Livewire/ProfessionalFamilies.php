<?php

namespace App\Http\Livewire;

use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProfessionalFamily;

class ProfessionalFamilies extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $professional_families = ProfessionalFamily::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($professional_families as $professional_family){
            $training_action = TrainingAction::where('professional_family_id', $professional_family['id']);
            if ($training_action){
                $professional_family['used'] = true;
            } else {
                $professional_family['used'] = false;
            }
        }
        return view('livewire.professional-families.view', [
            'professionalFamilies' => $professional_families,
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

        ProfessionalFamily::create([
			'name' => $this-> name
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Familia creado con exito.');
    }

    public function edit($id)
    {
        $record = ProfessionalFamily::findOrFail($id);

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
			$record = ProfessionalFamily::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Familia actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ProfessionalFamily::where('id', $id);
            $record->delete();
        }
    }
}

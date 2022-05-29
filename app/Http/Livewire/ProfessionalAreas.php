<?php

namespace App\Http\Livewire;

use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProfessionalArea;

class ProfessionalAreas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $professional_areas = ProfessionalArea::getProfessionalAreas($keyWord);
        return view('livewire.professional-areas.view', [
            'professionalAreas' => $professional_areas,
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
        ProfessionalArea::createProfessionalArea($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'ProfessionalArea Successfully created.');
    }

    public function edit($id)
    {
        $record = ProfessionalArea::findOrFail($id);

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
            ProfessionalArea::updateProfessionalArea($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'ProfessionalArea Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            ProfessionalArea::destroy($id);
        }
    }
}

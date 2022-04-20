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
        $professional_areas = ProfessionalArea::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($professional_areas as $professional_area){
            $training_action = TrainingAction::where('professional_area_id', $professional_area['id'])->first();
            if ($training_action){
                $professional_area['used'] = true;
            } else {
                $professional_area['used'] = false;
            }
        }
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

        ProfessionalArea::create([
			'name' => $this-> name
        ]);

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
			$record = ProfessionalArea::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'ProfessionalArea Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ProfessionalArea::where('id', $id);
            $record->delete();
        }
    }
}

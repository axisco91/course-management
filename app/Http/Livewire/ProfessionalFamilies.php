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
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $professional_families = ProfessionalFamily::getProfessionalFamilies($keyWord);
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

        $data = [
			'name' => $this-> name
        ];
        ProfessionalFamily::createProfessionalFamily($data);

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
            $data = [
                'name' => $this-> name
            ];
            ProfessionalFamily::updateProfessionalFamily($this->selected_id, $data);
            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Familia actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = ProfessionalFamily::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

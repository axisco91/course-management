<?php

namespace App\Http\Livewire;

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProfessionalCategory;

class ProfessionalCategories extends Component
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
        $professional_categories = ProfessionalCategory::getProfessionalCategories($keyWord);
        return view('livewire.professional-categories.view', [
            'professionalCategories' => $professional_categories,
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
        ProfessionalCategory::createProfessionalCategory($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'ProfessionalCategory Successfully created.');
    }

    public function edit($id)
    {
        $record = ProfessionalCategory::findOrFail($id);

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
            ProfessionalCategory::updateProfessionalCategory($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'ProfessionalCategory Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = ProfessionalCategory::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

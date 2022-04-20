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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

        $professional_categories = ProfessionalCategory::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($professional_categories as $professional_category){
            $student = Student::where('professional_category_id', $professional_category['id'])->first();
            if ($student) {
                $professional_category['used'] = true;
            } else {
                $professional_category['used'] = false;
            }
        }

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

        ProfessionalCategory::create([
			'name' => $this-> name
        ]);

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
			$record = ProfessionalCategory::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'ProfessionalCategory Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ProfessionalCategory::where('id', $id);
            $record->delete();
        }
    }
}

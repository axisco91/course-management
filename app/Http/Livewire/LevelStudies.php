<?php

namespace App\Http\Livewire;

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelStudy;

class LevelStudies extends Component
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
        $level_studies = LevelStudy::getLevelStudies($keyWord);
        return view('livewire.level-studies.view', [
            'levelStudies' => $level_studies,
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

        LevelStudy::createLevelStudy($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'LevelStudy Successfully created.');
    }

    public function edit($id)
    {
        $record = LevelStudy::findOrFail($id);

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

            LevelStudy::updateLevelStudy($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'LevelStudy Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = LevelStudy::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

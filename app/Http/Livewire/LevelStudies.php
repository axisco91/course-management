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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $level_studies = LevelStudy::orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($level_studies as $level_study){
            $student = Student::where('level_study_id', $level_study['id'])->first();
            if ($student){
                $level_study['used'] = true;
            } else {
               $level_study['used'] = false;
            }
        }
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

        LevelStudy::create([
			'name' => $this-> name
        ]);

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
			$record = LevelStudy::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'LevelStudy Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = LevelStudy::where('id', $id);
            $record->delete();
        }
    }
}

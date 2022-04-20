<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AreasTeacherArea;

class AreasTeacherAreas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $teacher_id, $teacher_area_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.areas-teacher-areas.view', [
            'areasTeacherAreas' => AreasTeacherArea::latest()
						->orWhere('teacher_id', 'LIKE', $keyWord)
						->orWhere('teacher_area_id', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->teacher_id = null;
		$this->teacher_area_id = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        AreasTeacherArea::create([
			'teacher_id' => $this-> teacher_id,
			'teacher_area_id' => $this-> teacher_area_id
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'AreasTeacherArea Successfully created.');
    }

    public function edit($id)
    {
        $record = AreasTeacherArea::findOrFail($id);

        $this->selected_id = $id;
		$this->teacher_id = $record-> teacher_id;
		$this->teacher_area_id = $record-> teacher_area_id;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = AreasTeacherArea::find($this->selected_id);
            $record->update([
			'teacher_id' => $this-> teacher_id,
			'teacher_area_id' => $this-> teacher_area_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'AreasTeacherArea Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = AreasTeacherArea::where('id', $id);
            $record->delete();
        }
    }
}

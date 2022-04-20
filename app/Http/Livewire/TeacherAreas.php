<?php

namespace App\Http\Livewire;

use App\Models\AreasTeacherArea;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TeacherArea;

class TeacherAreas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $teacher_areas = TeacherArea::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($teacher_areas as $teacher_area){
            $teacher = AreasTeacherArea::where('teacher_area_id', $teacher_area['id'])->first();
            if ($teacher){
                $teacher_area['used'] = true;
            } else {
                $teacher_area['used'] = false;
            }
        }
        return view('livewire.teacher-areas.view', [
            'teacherAreas' => $teacher_areas,
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

        TeacherArea::create([
			'name' => $this-> name
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'TeacherArea Successfully created.');
    }

    public function edit($id)
    {
        $record = TeacherArea::findOrFail($id);

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
			$record = TeacherArea::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'TeacherArea Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = TeacherArea::where('id', $id);
            $record->delete();
        }
    }
}

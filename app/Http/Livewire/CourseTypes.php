<?php

namespace App\Http\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseType;

class CourseTypes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $course_types = CourseType::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($course_types as $course_type){
            $course = Course::where('course_type_id', $course_type['id'])->first();
            if ($course){
                $course_type['used'] = true;
            } else {
                $course_type['used'] = false;
            }
        }
        return view('livewire.course-types.view', [
            'courseTypes' => $course_types,
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

        CourseType::create([
			'name' => $this-> name
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tipo actualizado con exito.');
    }

    public function edit($id)
    {
        $record = CourseType::findOrFail($id);

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
			$record = CourseType::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tipo actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CourseType::where('id', $id);
            $record->delete();
        }
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\CompanyType;
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
        $course_types = CourseType::getCourseTypes($keyWord);

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

        $data = [
			'name' => $this-> name
        ];
        CourseType::createCourseType($data);

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
			$data = [
			'name' => $this-> name
            ];

            CourseType::updateCourseType($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tipo actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            CourseType::destroy($id);
        }
    }
}

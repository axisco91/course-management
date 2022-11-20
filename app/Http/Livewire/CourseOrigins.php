<?php

namespace App\Http\Livewire;

use App\Models\CourseOrigin;
use Livewire\Component;
use Livewire\WithPagination;

class CourseOrigins extends Component
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
        $course_origins = CourseOrigin::getCourseOrigin($keyWord);
        return view('livewire.course-origins.view', [
            'course_origins' => $course_origins,
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

        CourseOrigin::createCourseOrigin($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Origen curso creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = CourseOrigin::findOrFail($id);

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
            CourseOrigin::updateCourseOrigin($this->selected_id, $data);

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Origen curso editado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = CourseOrigin::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseStatus;

class CourseStatuses extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

        $course_statuses = CourseStatus::getCourseStatuses($keyWord);
        return view('livewire.course-statuses.view', [
            'courseStatuses' => $course_statuses,
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

        CourseStatus::createCourseStatus($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'CourseStatus Successfully created.');
    }

    public function edit($id)
    {
        $record = CourseStatus::findOrFail($id);

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
            CourseStatus::updateCourseStatus($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'CourseStatus Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CourseStatus::where('id', $id);
            $record->delete();
        }
    }
}

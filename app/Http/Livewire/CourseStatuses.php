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
        $course_statuses = CourseStatus::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($course_statuses as $course_status){
            $course = Course::where('course_status_id', $course_status['id'])->first();
            if ($course){
                $course_status['used'] = true;
            } else {
                $course_status['used'] = false;
            }
        }

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

        CourseStatus::create([
			'name' => $this-> name
        ]);

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
			$record = CourseStatus::find($this->selected_id);
            $record->update([
			'name' => $this-> name
            ]);

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

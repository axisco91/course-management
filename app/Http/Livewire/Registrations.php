<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Registration;

class Registrations extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $student_id, $tracing_id, $chore_id, $price;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.registrations.view', [
            'registrations' => Registration::latest()
						->orWhere('course_id', 'LIKE', $keyWord)
						->orWhere('company_id', 'LIKE', $keyWord)
						->orWhere('student_id', 'LIKE', $keyWord)
						->orWhere('tracing_id', 'LIKE', $keyWord)
						->orWhere('chore_id', 'LIKE', $keyWord)
						->orWhere('price', 'LIKE', $keyWord)
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
		$this->course_id = null;
		$this->company_id = null;
		$this->student_id = null;
		$this->tracing_id = null;
		$this->chore_id = null;
		$this->price = null;
    }

    public function store()
    {
        $this->validate([
		'course_id' => 'required',
		'company_id' => 'required',
		'student_id' => 'required',
		'tracing_id' => 'required',
		'chore_id' => 'required',
		'price' => 'required',
        ]);

        Registration::create([
			'course_id' => $this-> course_id,
			'company_id' => $this-> company_id,
			'student_id' => $this-> student_id,
			'tracing_id' => $this-> tracing_id,
			'chore_id' => $this-> chore_id,
			'price' => $this-> price
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Registration Successfully created.');
    }

    public function edit($id)
    {
        $record = Registration::findOrFail($id);

        $this->selected_id = $id;
		$this->course_id = $record-> course_id;
		$this->company_id = $record-> company_id;
		$this->student_id = $record-> student_id;
		$this->tracing_id = $record-> tracing_id;
		$this->chore_id = $record-> chore_id;
		$this->price = $record-> price;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'course_id' => 'required',
		'company_id' => 'required',
		'student_id' => 'required',
		'tracing_id' => 'required',
		'chore_id' => 'required',
		'price' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Registration::find($this->selected_id);
            $record->update([
			'course_id' => $this-> course_id,
			'company_id' => $this-> company_id,
			'student_id' => $this-> student_id,
			'tracing_id' => $this-> tracing_id,
			'chore_id' => $this-> chore_id,
			'price' => $this-> price
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Registration Successfully updated.');
        }
    }
}

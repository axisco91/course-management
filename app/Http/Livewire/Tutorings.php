<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tutoring;

class Tutorings extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $tutorings = Tutoring::getTutorings($keyWord);
        return view('livewire.tutorings.view', [
            'tutorings' => $tutorings,
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
        Tutoring::createTutoring($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tutorización creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = Tutoring::findOrFail($id);

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
            Tutoring::updateTutoring($this->selected_id, $data);

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Tutorización actualziado con exito.');
            $this->emit('toastr', 'success');
        }
    }
}

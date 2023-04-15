<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Occupation;

class Occupations extends Component
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
       $occupations = Occupation::getOccupations($keyWord);
        return view('livewire.occupations.view', [
            'occupations' => $occupations,
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

        Occupation::createOccupation($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Ocupación creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = Occupation::findOrFail($id);

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
            Occupation::updateOccupation($this->selected_id, $data);

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Ocupación editado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = Occupation::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

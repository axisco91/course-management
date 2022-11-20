<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IncidenceType;

class IncidenceTypes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name;
    public $updateMode = false;
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
       $incidence_types = IncidenceType::getIncidenceTypes($keyWord, $this->sortBy, $this->sortDirection);
        return view('livewire.incidence-types.view', [
            'incidenceTypes' => $incidence_types,
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

        IncidenceType::createIncidenceType($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tipo creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = IncidenceType::findOrFail($id);

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
            IncidenceType::updateIncidenceType($this->selected_id, $data);
            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Tipo actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = IncidenceType::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }

    public function sortBy($field){
        if ($this->sortDirection == 'asc') {
            $this->sortDirection = 'desc';
        } else {
            $this->sortDirection = 'asc';
        }
        return $this->sortBy = $field;
    }
}

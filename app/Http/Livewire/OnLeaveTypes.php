<?php

namespace App\Http\Livewire;

use App\Models\OnLeaveType;
use Livewire\Component;
use Livewire\WithPagination;

class OnLeaveTypes extends Component
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
       $types = OnLeaveType::getOnLeaveTypes($keyWord);
        return view('livewire.on-leave-types.view', [
            'types' => $types,
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

        OnLeaveType::createOnLeaveType($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tipo creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = OnLeaveType::findOrFail($id);

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
            OnLeaveType::updateOnLeaveType($this->selected_id, $data);

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Tipo editado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = OnLeaveType::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

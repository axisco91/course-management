<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cnae;

class Cnaes extends Component
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
       $cnaes = Cnae::getCnaes($keyWord);
        return view('livewire.cnaes.view', [
            'cnaes' => $cnaes,
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

        Cnae::createCnae($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Cnae creado con exito.');
    }

    public function edit($id)
    {
        $record = Cnae::findOrFail($id);

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
            Cnae::updateCnaes($data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Cnae editado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = Cnae::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Center;

class Centers extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $address, $email, $telephone;
    public $updateMode = false;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

        $centers = Center::getCenters($keyWord);

        return view('livewire.centers.view', [
            'centers' => $centers,
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
		$this->address = null;
		$this->email = null;
		$this->telephone = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
        ]);

        $data = [
            'name' => $this-> name,
            'address' => $this-> address,
            'email' => $this-> email,
            'telephone' => $this-> telephone
        ];

        Center::createCenter($data);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Centro creado con exito.');
    }

    public function edit($id)
    {
        $record = Center::findOrFail($id);

        $this->selected_id = $id;
		$this->name = $record-> name;
		$this->address = $record-> address;
		$this->email = $record-> email;
		$this->telephone = $record-> telephone;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'name' => $this-> name,
                'address' => $this-> address,
                'email' => $this-> email,
                'telephone' => $this-> telephone
            ];

            Center::updateCenter($this->selected_id, $data);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Centro actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = Center::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }

    public function general($id){
        $record = Center::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->address = $record-> address;
        $this->email = $record-> email;
        $this->telephone = $record-> telephone;
    }
}

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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

        $centers = Center::
        orWhere('name', 'LIKE', $keyWord)
            ->orWhere('address', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->orWhere('telephone', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($centers as $center) {
            $course = Course::orWhere('delivery_center_id', $center['id'])
                ->orWhere('formation_center_id', $center['id'])->first();
            if ($course){
                $center['used'] = true;
            } else {
                $center['used'] = false;
            }
        }

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

        Center::create([
			'name' => $this-> name,
			'address' => $this-> address,
			'email' => $this-> email,
			'telephone' => $this-> telephone
        ]);

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
			$record = Center::find($this->selected_id);
            $record->update([
			'name' => $this-> name,
			'address' => $this-> address,
			'email' => $this-> email,
			'telephone' => $this-> telephone
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Centro actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Center::where('id', $id);
            $record->delete();
        }
    }
}

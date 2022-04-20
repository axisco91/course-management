<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $surname, $username, $email;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.users.view', [
            'users' => User::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('surname', 'LIKE', $keyWord)
						->orWhere('username', 'LIKE', $keyWord)
						->orWhere('email', 'LIKE', $keyWord)
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
		$this->name = null;
		$this->surname = null;
		$this->username = null;
		$this->email = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'surname' => 'required',
		'username' => 'required',
		'email' => 'required',
        ]);

        User::create([
			'name' => $this-> name,
			'surname' => $this-> surname,
			'username' => $this-> username,
			'email' => $this-> email
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'usuario creado con exito.');
    }

    public function edit($id)
    {
        $record = User::findOrFail($id);

        $this->selected_id = $id;
		$this->name = $record-> name;
		$this->surname = $record-> surname;
		$this->username = $record-> username;
		$this->email = $record-> email;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
		'surname' => 'required',
		'username' => 'required',
		'email' => 'required',
        ]);

        if ($this->selected_id) {
			$record = User::find($this->selected_id);
            $record->update([
			'name' => $this-> name,
			'surname' => $this-> surname,
			'username' => $this-> username,
			'email' => $this-> email
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Usuario editado con exito.');
        }
    }
}

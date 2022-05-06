<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $surname, $username, $email, $create_role_id, $role_id, $password;
    public $updateMode = false;
    public $roles;

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

    public function mount(){
        $this->roles = Role::all();
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
        $this->create_role_id = null;
        $this->role_id = null;
        $this->password = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'surname' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $this-> name,
            'surname' => $this-> surname,
            'username' => $this-> username,
            'email' => $this-> email,
            'password' => Hash::make($this-> password),
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
        $users_roles = $record->getRoleNames();
        $roles = [];
        foreach ($users_roles as $user_role){
            array_push($roles, $user_role);
        }
        $this->role_id = $roles;

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
            $record->syncRoles($this->role_id);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Usuario editado con exito.');
        }
    }
}

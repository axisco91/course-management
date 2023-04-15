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
    public $selected_id, $keyWord, $name, $surname, $username, $email, $create_role_id, $role_id, $password, $password_confirmation, $commission, $has_commission;
    public $updateMode = false;
    public $roles;

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $users = User::getUsers($keyWord);
        return view('livewire.users.view', [
            'users' => $users,
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
        $this->password_confirmation = null;
        $this->commission = null;
        $this->has_commission = null;

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

        if ($this->username){
            $user = User::findUser($this->username);
            if ($user){
                $this->emit('alreadyExists', 'user');
                return;
            }
        }

        $data = [
            'name' => $this-> name,
            'surname' => $this-> surname,
            'username' => $this-> username,
            'email' => $this-> email,
            'password' => Hash::make($this-> password),
            'role_id' => $this->role_id,
            'has_commission' => $this->has_commission ? $this->has_commission : 0,
            'commission' => $this->commission
        ];
        User::createUser($data);
        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'usuario creado con exito.');
        $this->emit('toastr', 'success');
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
        $this->commission = $record->commission;
        $this->has_commission = $record-> has_commission == 1 ? $record-> has_commission : null;
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
            if ($this->username) {
                $user = User::findUser($this->username, $this->selected_id);
                if ($user) {
                    $this->emit('alreadyExists', 'user');
                    return;
                }
            }

            $data = [
                'name' => $this->name,
                'surname' => $this->surname,
                'username' => $this->username,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'has_commission' => $this->has_commission ? $this->has_commission : 0,
                'commission' => $this->commission
            ];
            User::updateUser($this->selected_id, $data);

           // $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
            session()->flash('message', 'Usuario editado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function changePassword($id){
        $this->selected_id = $id;
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function saveChangePassword(){
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
            ]);

        if ($this->selected_id){
            $this->prueba = 'llega';
            $user = User::find($this->selected_id);
            $user->update([
                'password' => Hash::make($this->password),
            ]);
            $this->emit('closePasswordModal');
            $this->updateMode = false;
            session()->flash('message', 'Contraseña cambiado con exito.');
            $this->emit('toastr', 'success');
        }
    }
}

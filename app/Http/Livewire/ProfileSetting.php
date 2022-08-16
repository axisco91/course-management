<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ProfileSetting extends Component
{

    public $selected_id, $name, $surname, $username, $email, $password, $password_confirmation;
    public $updateMode = false;
    public $roles;

    public function render()
    {
        return view('livewire.profile.setting');
    }

    public function mount(){
        $id = Auth::user()->id;
        if ($id){
            $this->selected_id = $id;
            $user = User::find($id);
            $this->name = $user-> name;
            $this->surname = $user-> surname;
            $this->username = $user-> username;
            $this->email = $user-> email;
        }
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
                'role_id' => $this->role_id
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

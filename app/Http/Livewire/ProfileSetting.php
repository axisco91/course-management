<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithFileUploads;
use App\Photo;
class ProfileSetting extends Component
{

    public $selected_id, $name, $surname, $username, $email, $password, $password_confirmation, $photo;
    public $updateMode = false;
    public $roles;
    protected $listeners = [
        'saveChangePassword' => 'saveChangePassword'
    ];

    use WithFileUploads;

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

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->password = null;
        $this->password_confirmation = null;
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
                }
            }

            $data = [
                'name' => $this->name,
                'surname' => $this->surname,
                'username' => $this->username,
                'email' => $this->email
            ];
            User::updateUser($this->selected_id, $data);
            $this->emit('closeModal');
            session()->flash('message', 'Usuario editado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function changePassword(){
        $this->password = '';
        $this->password_confirmation = '';
    }



    public function saveChangePassword(){
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($this->selected_id){
            $user = User::find($this->selected_id);
            $user->update([
                'password' => Hash::make($this->password),
            ]);
            $this->updateMode = false;
            $this->emit('closeModal');
            session()->flash('message', 'Contraseña cambiado con exito.');
            $this->emit('toastr', 'success');
        }
    }
}

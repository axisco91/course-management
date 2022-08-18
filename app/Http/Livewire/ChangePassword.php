<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
class ChangePassword extends Component
{

    public $selected_id, $password, $password_confirmation;
    public $updateMode = false;
    public $roles;

    public function render()
    {
        return view('livewire.profile.change-password');
    }

    public function mount(){
        $id = Auth::user()->id;
        $this->selected_id = $id;
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
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($this->password != $this->password_confirmation) {
            return;
        }

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

    public function changePassword(){
        $this->password = '';
        $this->password_confirmation = '';
    }
}

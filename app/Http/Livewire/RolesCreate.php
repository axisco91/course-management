<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesCreate extends Component
{

    public $name;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.roles.create');
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

        Role::create([
            'name' => $this-> name,
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        $this->emit('updateRolesList');
        session()->flash('message', 'Role creado con exito.');
    }
}

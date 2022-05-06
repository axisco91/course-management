<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{

    protected $paginationTheme = 'bootstrap', $listeners = ['role' => 'role'];
    public $selected_id, $keyWord, $selected = [], $role;

    public function render()
    {
        $permissions = Permission::all();
        return view('livewire.permissions.view', [
            'permissions' => $permissions,
        ]);
    }

    public function role($id){
        $this->role = Role::find($id);
        $this->selected_id = $id;
        $this->selected = $this->role->permissions->pluck('id');
    }

    public function cancel()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->permission = null;
    }

    public function store()
    {
        if ($this->selected_id) {
            $record = Role::find($this->selected_id);
            $record->syncPermissions($this->selected);
            $this->resetInput();
            session()->flash('message', 'Permissos guardados con exitos.');
        }
    }
}

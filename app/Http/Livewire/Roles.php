<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap', $listeners = ['updateRolesList' => 'updateRolesList', 'destroy' => 'destroy'];
    public $selected_id, $keyWord, $permission, $name;
    public $updateMode = false;

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $roles = Role::latest()
            ->orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        return view('livewire.roles.view', [
            'roles' => $roles,
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
    }

    public function updateRolesList(){
        $roles = Role::latest()
            ->orWhere('name', 'LIKE', $this->keyWord)
            ->paginate(10);
    }

    public function edit($id)
    {
        $record = Role::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Role::find($this->selected_id);
            $record->update([
                'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Rol actualizado con exito.');
        }
    }
    public function destroy($id)
    {
        if ($id) {
            $value = Role::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }

    public function roleId($id){
        if ($id){
            $this->emit('role', $id);
        }
    }
}

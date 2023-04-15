<?php

namespace App\Http\Livewire;

use App\Models\CertificationElement;
use App\Models\Module;
use Livewire\Component;
use Livewire\WithPagination;

class Modules extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $formative_module, $name, $total_hours, $face_to_face_hours, $exam_hours, $teletraining_hours, $tutoring_hours, $active, $training_units;
    public $updateMode = false;
    public $tab = 'info';
    protected $listeners = [
        'changeState' => 'changeState',
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $modules = Module::getModules($keyWord);

        foreach ($modules as $module) {
            $used = CertificationElement::where('module_id', $module->id)->first();
            if ($used) {
                $module['used'] = true;
            } else {
                $module['used'] = false;
            }
        }

        return view('livewire.modules.list', [
            'modules' => $modules,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){

    }

    private function resetInput()
    {
		$this->name = null;
		$this->total_hours = null;
        $this->active = null;
        $this->face_to_face_hours = null;
        $this->exam_hours = null;
        $this->teletraining_hours = null;
        $this->tutoring_hours = null;
        $this->formative_module = null;
        $this->training_units = null;
    }

    public function general($id)
    {
        $record = Module::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->total_hours = $record->total_hours;
        $this->active = $record->active;
        $this->face_to_face_hours = $record->face_to_face_hours;
        $this->exam_hours = $record->exam_hours;
        $this->teletraining_hours = $record->teletraining_hours;
        $this->tutoring_hours = $record->tutoring_hours;
        $this->formative_module = $record->formative_module;
        $this->training_units = $record->trainingUnits()->get();
    }

    public function getInfo($id){
        $this->emit('getModuleInfo', $id);
        $module = Module::find($id);
        $this->name = $module->name;
        $this->active = $module->active;
        $this->selected_id = $id;
        $this->total_hours = $module->total_hours;
        $this->face_to_face_hours = $module->face_to_face_hours;
        $this->exam_hours = $module->exam_hours;
        $this->teletraining_hours = $module->teletraining_hours;
        $this->tutoring_hours = $module->tutoring_hours;
        $this->formative_module = $module->formative_module;
    }

    public function changeState($id){
        $module = Module::find($id);
        if ($module->active == 0){
            $module->update([
                'active' => 1
            ]);
            session()->flash('message', 'Modulo activado con exito.');
            $value = 'activated';
        } else {
            $module->update([
                'active' => 0
            ]);
            session()->flash('message', 'Modulo desactivado con exito.');
            $value = 'desactivated';
        }
        $this->dispatchBrowserEvent('status-update', ['value' => $value]);
    }

    public function destroy($id){
        if ($id) {
            Module::destroy($id);
            return 1;
        }
    }
}

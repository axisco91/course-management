<?php

namespace App\Http\Livewire;

use App\Models\Module;
use App\Models\TrainingUnit;
use App\Models\TrainingUnitsModule;
use Livewire\Component;

class ModulesUpdate extends Component
{
    public $selected_id, $formative_module, $name, $total_hours, $face_to_face_hours, $exam_hours, $teletraining_hours,
        $tutoring_hours, $active, $training_units, $modules_training_units, $training_unit_id;
    protected $listeners = ['addTrainingUnit' => 'addTrainingUnit'];

    public function render()
    {
        return view('livewire.modules.update');
    }

    public function mount($id){
        $this->selected_id = $id;
        if ($this->selected_id){
            $record = Module::findOrFail($this->selected_id);

            $this->name = $record-> name;
            $this->total_hours = $record->total_hours;
            $this->active = $record->active;
            $this->face_to_face_hours = $record->face_to_face_hours;
            $this->exam_hours = $record->exam_hours;
            $this->teletraining_hours = $record->teletraining_hours;
            $this->tutoring_hours = $record->tutoring_hours;
            $this->formative_module = $record->formative_module;
            $this->modules_training_units = TrainingUnitsModule::getTrainingUnitModules($this->selected_id);
            $this->training_units = TrainingUnit::getTrainingUnitsNotInModule($this->selected_id);
        }
    }


    public function update()
    {
        $this->validate([
            'formative_module' => 'required',
            'name' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'formative_module' => $this->formative_module,
                'name' => $this-> name,
                'active' => $this-> active == true ? 1 : 0,
                'exam_hours' => $this->exam_hours,
                'tutoring_hours' => $this->tutoring_hours,
                'tutoring_hours' => $this->tutoring_hours,
                'teletraining_hours' => $this->teletraining_hours
            ];

            Module::updateModule($this->selected_id, $data);

            session()->flash('message', 'Modulo actualizada con exito.');
            $this->emit('toastr', 'success');
        }
    }
    public function setTotalHours($face_to_face, $teletraining) {
        $this->total_hours = $face_to_face+$teletraining;
    }

    public function addTrainingUnit($id){
        if ($id){
            $training_unit = TrainingUnitsModule::createTrainingUnitModule($this->selected_id, $id);
            redirect(request()->header('Referer'));
        }
    }

    public function unregister($training_unit_module_id){
        TrainingUnitsModule::deleteTrainingUnitModule($this->selected_id, $training_unit_module_id);
        redirect(request()->header('Referer'));
    }
}

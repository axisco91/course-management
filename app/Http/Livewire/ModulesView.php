<?php

namespace App\Http\Livewire;

use App\Models\Module;
use App\Models\TrainingUnit;
use App\Models\TrainingUnitsModule;
use Livewire\Component;

class ModulesView extends Component
{
    public $selected_id, $formative_module, $name, $total_hours, $face_to_face_hours, $exam_hours, $teletraining_hours,
        $tutoring_hours, $active, $training_units, $modules_training_units, $training_unit_id;

    public function render()
    {
        return view('livewire.modules.view');
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
}

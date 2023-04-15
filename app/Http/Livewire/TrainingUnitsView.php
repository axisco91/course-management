<?php

namespace App\Http\Livewire;

use App\Models\TrainingUnit;
use Livewire\Component;

class TrainingUnitsView extends Component
{
    public $selected_id, $formative_unit, $name, $total_hours, $face_to_face_hours, $exam_hours, $teletraining_hours,
        $tutoring_hours, $active;

    public function render()
    {
        return view('livewire.training-units.view');
    }

    public function mount($id){
        $this->selected_id = $id;
        if ($this->selected_id){
            $record = TrainingUnit::findOrFail($this->selected_id);

            $this->name = $record-> name;
            $this->total_hours = $record->total_hours;
            $this->active = $record->active;
            $this->face_to_face_hours = $record->face_to_face_hours;
            $this->exam_hours = $record->exam_hours;
            $this->teletraining_hours = $record->teletraining_hours;
            $this->tutoring_hours = $record->tutoring_hours;
            $this->formative_unit = $record->formative_unit;
        }
    }
}

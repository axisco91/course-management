<?php

namespace App\Http\Livewire;

use App\Models\ActionType;
use App\Models\CourseOrigin;
use App\Models\Modality;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\Provider;
use App\Models\TrainingActionGroup;
use App\Models\TrainingActionLevel;
use App\Models\TrainingUnit;
use App\Models\Tutoring;
use App\Models\WebPlatform;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TrainingUnitsUpdate extends Component
{
    public $selected_id, $formative_unit, $name, $total_hours, $face_to_face_hours, $exam_hours, $teletraining_hours,
        $tutoring_hours, $active, $tab = 'update';

    public function render()
    {
        return view('livewire.training-units.update');
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

    public function update()
    {
        $this->validate([
            'formative_unit' => 'required',
            'name' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'formative_unit' => $this->formative_unit,
                'name' => $this-> name,
                'active' => $this-> active == true ? 1 : 0,
                'teletraining_hours' => $this-> teletraining_hours,
                'exam_hours' => $this-> exam_hours,
                'tutoring_hours' => $this-> tutoring_hours,
            ];

            $training_unit = TrainingUnit::updateTrainingUnit($this->selected_id, $data);
            session()->flash('message', 'Unidad formativa actualizada con exito.');
            $this->emit('toastr', 'success');
        }
    }
    public function setTotalHours($tutoring_hours, $exam_hours, $teletraining_hours) {
        $this->face_to_face_hours = $tutoring_hours+$exam_hours;
        $this->total_hours = $this->face_to_face+$teletraining_hours;
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\TrainingUnit;
use Livewire\Component;

class TrainingUnitsCreate extends Component
{

    public $selected_id, $formative_unit, $name, $total_hours, $face_to_face_hours, $exam_hours, $teletraining_hours, $tutoring_hours, $active = 1;

    public function render()
    {
        return view('livewire.training-units.create');
    }
    public function mount(){
    }

    public function store()
    {
        $this->validate([
            'formative_unit' => 'required',
            'name' => 'required'
        ]);

        $total_hours = $this-> exam_hours + $this-> tutoring_hours + $this-> teletraining_hours;

        $unit = TrainingUnit::create([
            'formative_unit' => $this->formative_unit,
            'name' => $this-> name,
            'active' => $this-> active == true ? 1 : 0,
            'face_to_face_hours' => $this-> exam_hours + $this-> tutoring_hours,
            'teletraining_hours' => $this-> teletraining_hours,
            'total_hours' => $total_hours,
            'exam_hours' => $this-> exam_hours,
            'tutoring_hours' => $this-> tutoring_hours,
        ]);

        session()->flash('message', 'Unidad creado con exito.');
        return redirect('/training_units/edit/'.$unit['id']);
        $this->emit('toastr', 'success');
    }

    public function setTotalHours($tutoring_hours, $exam_hours, $teletraining_hours) {
        $this->face_to_face_hours = $tutoring_hours+$exam_hours;
        $this->total_hours = $this->face_to_face+$teletraining_hours;
    }
}

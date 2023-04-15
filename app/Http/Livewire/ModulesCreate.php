<?php

namespace App\Http\Livewire;

use App\Models\Module;
use Livewire\Component;

class ModulesCreate extends Component
{
    public $selected_id, $formative_module, $name, $total_hours = 0, $face_to_face_hours = 0, $exam_hours = 0, $teletraining_hours = 0, $tutoring_hours = 0, $active = 1;

    public function render()
    {
        return view('livewire.modules.create');
    }

    public function mount(){

    }

    public function store()
    {
        $this->validate([
        'formative_module' => 'required',
		'name' => 'required'
        ]);

        $module = Module::create([
            'formative_module' => $this->formative_module,
			'name' => $this-> name,
			'active' => $this-> active == true ? 1 : 0,
            'exam_hours' => $this->exam_hours,
            'tutoring_hours' => $this->tutoring_hours,
            'face_to_face_hours' => $this->exam_hours + $this->tutoring_hours,
            'teletraining_hours' => $this->teletraining_hours,
            'total_hours' => $this->exam_hours + $this->tutoring_hours + $this->teletraining_hours
        ]);

		session()->flash('message', 'Modulo creado con exito.');
        $this->emit('toastr', 'success');
        return redirect('/modules/edit/'.$module['id']);
    }

    public function setTotalHours($face_to_face, $teletraining) {
        $this->total_hours = $face_to_face+$teletraining;
    }
}

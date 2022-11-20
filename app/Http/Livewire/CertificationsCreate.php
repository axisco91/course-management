<?php

namespace App\Http\Livewire;

use App\Models\Certification;
use Livewire\Component;

class CertificationsCreate extends Component
{
    public $selected_id, $name, $total_hours, $active = 1;

    public function render()
    {
        return view('livewire.certifications.create');
    }

    public function mount(){

    }

    public function store()
    {
        $this->validate([
		'name' => 'required'
        ]);

        $certification = Certification::create([
			'name' => $this-> name,
			'active' => $this-> active == true ? 1 : 0,
        ]);
		session()->flash('message', 'Certificado creado con exito.');
        $this->emit('toastr', 'success');
        return redirect('/certifications/edit/'.$certification['id']);
    }

    public function setTotalHours($face_to_face, $teletraining) {
        $this->total_hours = $face_to_face+$teletraining;
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Certification;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\TrainingActionLevel;
use Livewire\Component;

class CertificationsCreate extends Component
{
    public $selected_id, $name, $total_hours, $active = 1, $professional_family_id, $professional_area_id,
        $level, $professional_families, $professional_areas, $code;

    public function render()
    {
        return view('livewire.certifications.create');
    }

    public function mount(){
        $this->professional_families = ProfessionalFamily::all();
        $this->professional_areas = ProfessionalArea::all();
    }

    public function store()
    {
        $this->validate([
		    'name' => 'required',
            'professional_family_id' => 'required|not_in:-1'
        ]);

        $certification = Certification::create([
			'name' => $this-> name,
			'active' => $this-> active == true ? 1 : 0,
            'professional_family_id' => $this->professional_family_id,
            'professional_area_id' => $this->professional_area_id,
            'level' => $this->level,
            'code' => $this->code
        ]);
		session()->flash('message', 'Certificado creado con exito.');
        $this->emit('toastr', 'success');
        return redirect('/certifications/edit/'.$certification['id']);
    }
}

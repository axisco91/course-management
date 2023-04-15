<?php

namespace App\Http\Livewire;

use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\TrainingActionLevel;
use Livewire\Component;
use Livewire\WithPagination;

class Certifications extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $total_hours, $active, $certification_elements = [],
        $professional_family_id, $professional_area_id, $level, $professional_families, $professional_areas, $code;
    public $tab = 'info';
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $certifications = Certification::getCertifications($keyWord);

        return view('livewire.certifications.list', [
            'certifications' => $certifications,
        ]);
    }

    public function mount(){
        $this->professional_families = ProfessionalFamily::all();
        $this->professional_areas = ProfessionalArea::all();
    }

    public function general($id)
    {
        $record = Certification::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->total_hours = $record->total_hours;
        $this->active = $record->active;
        $this->professional_family_id = $record->professional_family_id;
        $this->professional_area_id = $record->professional_area_id;
        $this->level = $record->level;
        $this->code = $record->code;
        $this->certification_elements = CertificationElement::getCertificationElement($this->selected_id);
    }

    public function getInfo($id){
        $this->emit('getCertificationInfo', $id);
        $module = Certification::find($id);
        $this->name = $module->name;
        $this->active = $module->active;
        $this->selected_id = $id;
        $this->total_hours = $module->total_hours;
    }

    public function changeState($id){
        $certification = Certification::find($id);
        if ($certification->active == 0){
            $certification->update([
                'active' => 1
            ]);
            session()->flash('message', 'Certificado activado con exito.');
            $value = 'activated';
        } else {
            $certification->update([
                'active' => 0
            ]);
            session()->flash('message', 'Certificado desactivado con exito.');
            $value = 'desactivated';
        }
        $this->dispatchBrowserEvent('status-update', ['value' => $value]);
    }

    public function destroy($id){
        if ($id) {
            Certification::destroy($id);
            return 1;
        }
    }

    public function resetInput(){
        $this->selected_id = null;
        $this->name = null;
        $this->total_hours = null;
        $this->certification_elements = null;
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
}

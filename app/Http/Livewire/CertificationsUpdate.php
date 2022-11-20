<?php

namespace App\Http\Livewire;

use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\Module;
use App\Models\TrainingUnit;
use Livewire\Component;

class CertificationsUpdate extends Component
{
    public $selected_id, $name, $total_hours, $active, $training_units, $modules, $training_unit_id, $module_id, $certification_elements;
    protected $listeners = ['addElement' => 'addElement'];

    public function render()
    {
        return view('livewire.certifications.update');
    }

    public function mount($id){
        $this->selected_id = $id;
        if ($this->selected_id){
            $record = Certification::findOrFail($this->selected_id);

            $this->name = $record-> name;
            $this->total_hours = $record->total_hours;
            $this->active = $record->active;
            $this->modules = Module::getModulesNotInCertification($this->selected_id);
            $this->certification_elements = CertificationElement::getCertificationElement($this->selected_id);
            $this->training_units = TrainingUnit::getTrainingUnitsNotInCertification($this->selected_id);
        }
    }


    public function update()
    {
        $this->validate([
            'name' => 'required',
        ]);

       // $total_hours = $this-> face_to_face_hours + $this-> teletraining_hours;

        if ($this->selected_id) {
            $data = [
                'code' => $this->code,
                'name' => $this-> name,
                'active' => $this-> active == true ? 1 : 0,
                'exam_hours' => $this-> exam_hours,
                'tutoring_hours' => $this-> tutoring_hours,
            ];
            Module::updateModule($this->selected_id, $data);

            session()->flash('message', 'Modulo actualizada con exito.');
            $this->emit('toastr', 'success');
        }
    }
    public function setTotalHours($face_to_face, $teletraining) {
        $this->total_hours = $face_to_face+$teletraining;
        $this->create_total_hours = $face_to_face+$teletraining;
    }

    public function addElement($id, $type){
        if ($id && $type){
            $training_action = CertificationElement::createCertificationElement($this->selected_id, $id, $type);
            redirect(request()->header('Referer'));
        }
    }

    public function unregister($id){
        CertificationElement::deleteCertificationElement($this->selected_id, $id);
        redirect(request()->header('Referer'));
    }
}

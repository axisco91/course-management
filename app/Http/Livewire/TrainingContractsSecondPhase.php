<?php

namespace App\Http\Livewire;

use App\Models\ActionType;
use App\Models\Advisor;
use App\Models\Certification;
use App\Models\Company;
use App\Models\ExcludedDay;
use App\Models\Modality;
use App\Models\Occupation;
use App\Models\OnLeaveType;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\Provider;
use App\Models\Province;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TrainingActionGroup;
use App\Models\TrainingActionLevel;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractSpecialty;
use App\Models\TrainingContractStatus;
use App\Models\Tutoring;
use App\Models\User;
use App\Models\WebPlatform;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TrainingAction;

class TrainingContractsSecondPhase extends Component
{
    public $selected_id, $specialty_id, $certification_id, $contract_type, $training_contract_certifications,
        $specialty, $professional_certificate;
    public $updateMode = false;
    public $certifications, $specialties;
    protected $listeners = ['addElement' => 'addElement'];
    public function render()
    {
        return view('livewire.training-contracts.second-phase');
    }

    public function mount($id){
        $this->selected_id = $id;
        $this->specialties = TrainingAction::getSpecialties($this->selected_id);
        $this->certifications = Certification::getCertificationsNotinTrainingContract($this->selected_id);
        $this->training_contract_certifications = TrainingContractElement::getTrainingContractElements($this->selected_id);
        $record = TrainingContract::findOrFail($id);
        $this->specialty = $record->specialty;
        $this->professional_certificate = $record->professional_certificate;
        $this->contract_type = $record->contract_type;
    }

    public function addElement($id, $type){
        if ($id && $type){
            $training_contract = TrainingContractElement::createTrainingContractElement($this->selected_id, $id, $type);
            redirect(request()->header('Referer'));
        }
    }
    public function unregister($id){
        TrainingContractElement::deleteTrainingContractElement($this->selected_id, $id);
        redirect(request()->header('Referer'));
    }
}

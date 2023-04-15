<?php

namespace App\Http\Livewire;

use App\Models\ActionType;
use App\Models\Advisor;
use App\Models\Center;
use App\Models\Certification;
use App\Models\Company;
use App\Models\ExamTutorial;
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

class ExamsTutorialsUpdate extends Component
{
    public $selected_id, $specialty_id, $certification_id, $contract_type, $training_contract_certifications,
        $specialty, $professional_certificate, $total_hours, $exams_tutorials, $centers, $type, $center_id, $date, $beginning, $end,
        $exam_tutorial_id;
    public $updateMode = false;
    public $certifications, $specialties;
    public function render()
    {
        if ($this->selected_id){
            $this->exams_tutorials = ExamTutorial::getExamTutorials($this->selected_id);
        }
        return view('livewire.exams-tutorials.update');
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
        $this->total_hours = $record->total_hours;
        $this->centers = Center::all();
    }

    public function resetInput(){
        $this->type = null;
        $this->center_id = null;
        $this->date = null;
        $this->beginning = null;
        $this->end = null;
        $this->exam_tutorial_id = null;
    }

    public function edit($id){
        $this->exam_tutorial_id = $id;
        $exam_tutorial = ExamTutorial::find($id);
        $this->center_id = $exam_tutorial->center_id;
        $this->date = $exam_tutorial->date;
        $this->type = $exam_tutorial->type;
        $this->date = $exam_tutorial->date;
        $this->beginning = $exam_tutorial->beginning;
        $this->end = $exam_tutorial->end;
    }

    public function update(){
        $this->validate([
            'center_id' => 'required|not_in:-1',
            'type' => 'required|not_in:-1',
            'date' => 'required',
            'beginning' => 'required'
        ]);
        $exam_tutorial = ExamTutorial::find($this->exam_tutorial_id);
        $exam_tutorial->update([
            'center_id' => $this->center_id,
            'type' => $this->type,
            'date' => $this->date,
            'beginning' => $this->beginning,
            'end' => $this->end
        ]);
        session()->flash('message', 'actualizado con exito.');
        $this->emit('toastr', 'success');
        $this->resetInput();
    }
}

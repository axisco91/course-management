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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TrainingAction;

class ExamsTutorialsCreate extends Component
{
    public $selected_id, $centers, $type, $center_id, $date, $beginning, $end;
    public function render()
    {
        if ($this->selected_id){
            $this->exams_tutorials = ExamTutorial::getExamTutorials($this->selected_id);
        }
        return view('livewire.exams-tutorials.create');
    }

    public function mount($training_contract_id){
        $this->selected_id = $training_contract_id;
        $this->centers = Center::all();
    }

    public function resetInput(){
        $this->type = null;
        $this->center_id = null;
        $this->date = null;
        $this->beginning = null;
        $this->end = null;
    }
    public function store(){
        $this->validate([
            'center_id' => 'required|not_in:-1',
            'type' => 'required|not_in:-1',
            'date' => 'required',
            'beginning' => 'required'
        ]);
        ExamTutorial::create([
            'training_contract_id' => $this->selected_id,
            'center_id' => $this->center_id,
            'type' => $this->type,
            'date' => $this->date,
            'beginning' => Carbon::createFromFormat('H:i:s', $this->beginning.':00')->toTimeString(),
            'end' => Carbon::createFromFormat('H:i:s', $this->end.':00')->toTimeString()
        ]);
        session()->flash('message', 'creado con exito.');
        $this->emit('toastr', 'success');
        $this->resetInput();
    }
}

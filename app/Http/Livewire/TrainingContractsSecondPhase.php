<?php

namespace App\Http\Livewire;

use App\Models\Certification;
use App\Models\ExamTutorial;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractsExcludedDay;
use App\Models\TrainingContractSpecialty;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\TrainingAction;

class TrainingContractsSecondPhase extends Component
{
    public $selected_id, $specialty_id, $certification_id, $contract_type, $training_contract_certifications,
        $specialty, $professional_certificate, $total_hours, $exams_tutorials, $exam_tutorial_id, $total_days, $daily_hours;
    public $updateMode = false;
    public $certifications, $specialties;
    protected $listeners = ['addElement' => 'addElement', 'destroy' => 'destroy'];
    public function render()
    {
        if ($this->selected_id){
            $this->exams_tutorials = ExamTutorial::getExamTutorials($this->selected_id);
        }
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
        $this->total_hours = $record->total_hours;
        $this->total_days = $record->total_days;
        $this->daily_hours = $record->daily_hours;
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

    public function calculate(){
       $record = TrainingContract::findOrFail($this->selected_id);
       $cont_days = 0;
       $date = Carbon::parse($record->beginning_formation);
       $end_date = Carbon::parse($record->end_formation);
        $hours_days = 0;
       do {
           $excluded = TrainingContractsExcludedDay::nonWorkingDay($this->selected_id, $date);
           if ($excluded != true){
               switch($date->dayOfWeek){
                   case 0:
                       if ($record->sunday == 1){
                           $cont_days++;
                       }
                       break;
                   case 1:
                       if ($record->monday == 1){
                           $cont_days++;
                       }
                       break;
                   case 2:
                       if ($record->tuesday == 1){
                           $cont_days++;
                       }
                       break;
                   case 3:
                       if ($record->wednesday == 1){
                           $cont_days++;
                       }
                       break;
                   case 4:
                       if ($record->thursday == 1){
                           $cont_days++;
                       }
                       break;
                   case 5:
                       if ($record->friday == 1){
                           $cont_days++;
                       }
                       break;
                   case 6:
                       if ($record->saturday == 1){
                           $cont_days++;
                       }
                       break;
               }
           }
           $date->addDay();
       } while($end_date->gt($date));
       if ($cont_days != 0){
           $hours_days = $record->total_hours / $cont_days;
           $hours_days = floor($hours_days * 100) / 100;
           $record->update([
               'total_days' => $cont_days,
               'daily_hours' => $hours_days
           ]);
           $this->total_days = $cont_days;
           $this->daily_hours = $hours_days;
       }
       foreach($this->training_contract_certifications as $training_element){
           if ($training_element === $this->training_contract_certifications[0]){
               $beginning = Carbon::parse($record->beginning_formation);
           }
           $training_element->update([
               'beginning' => $beginning
           ]);

           if ($training_element->training_action_id){
               $training_action = TrainingAction::find($training_element->training_action_id);
               $total_hours = $training_action->total_hours;
               $total_days = $total_hours / $hours_days;
           } else if($training_element->certification_id) {
               $certification = Certification::find($training_element->certification_id);
               $total_hours = $certification->total_hours;
               $total_days = $total_hours / $hours_days;
           } else {
               break;
           }
           $total_days = round($total_days);
           $training_element->update([
               'total_days' => $total_days
           ]);
           while($total_days != 0){
               $excluded = TrainingContractsExcludedDay::nonWorkingDay($this->selected_id, $date);
               if ($excluded != true){
                   switch($beginning->dayOfWeek){
                       case 0:
                           if ($record->sunday == 1){
                               $total_days--;
                           }
                           break;
                       case 1:
                           if ($record->monday == 1){
                               $total_days--;
                           }
                           break;
                       case 2:
                           if ($record->tuesday == 1){
                               $total_days--;
                           }
                           break;
                       case 3:
                           if ($record->wednesday == 1){
                               $total_days--;
                           }
                           break;
                       case 4:
                           if ($record->thursday == 1){
                               $total_days--;
                           }
                           break;
                       case 5:
                           if ($record->friday == 1){
                               $total_days--;
                           }
                           break;
                       case 6:
                           if ($record->saturday == 1){
                               $total_days--;
                           }
                           break;
                   }
               }
               $end = $beginning->addDay();
           }
           $training_element->update([
               'end' => $end
           ]);
           $beginning = $beginning->addDay();
       }
       redirect(request()->header('Referer'));
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
            'beginning' => $this->beginning,
            'end' => $this->end
        ]);
        session()->flash('message', 'creado con exito.');
        $this->emit('toastr', 'success');
        $this->resetInput();
    }

    public function new(){
        $this->resetInput();
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

    public function destroy($id){
        ExamTutorial::destroy($id);
    }
}

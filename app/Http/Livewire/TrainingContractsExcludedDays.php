<?php

namespace App\Http\Livewire;

use App\Models\ActionType;
use App\Models\Advisor;
use App\Models\BankHolidayGroup;
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
use App\Models\TrainingContractsExcludedDay;
use App\Models\TrainingContractSpecialty;
use App\Models\TrainingContractStatus;
use App\Models\Tutoring;
use App\Models\User;
use App\Models\WebPlatform;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TrainingAction;

class TrainingContractsExcludedDays extends Component
{
    public $selected_id, $excluded_day_id, $provinces, $province_id, $excluded_days, $date, $groups, $group_id;
    protected $listeners = ['addElement' => 'addElement'];
    public function render()
    {
        if ($this->selected_id){
            $this->excluded_days = TrainingContractsExcludedDay::select('training_contracts_excluded_days.*', 'excluded_days.day')
                ->leftJoin('excluded_days', 'excluded_days.id', '=', 'training_contracts_excluded_days.excluded_day_id')
                ->where('training_contract_id', $this->selected_id)->get();
        }
        return view('livewire.training-contracts.excluded-days');
    }

    public function mount($id){
        $this->selected_id = $id;
        $record = TrainingContract::find($id);
        $this->provinces = Province::getProvincesWithExcludedDays($record->beginning, $record->end);
        $this->groups = BankHolidayGroup::getBankHolidayGroup($record->beginning, $record->end);
    }

    public function addElement($id, $type){
        if ($id){
            $training_contract = TrainingContract::find($this->selected_id);
            TrainingContractsExcludedDay::createTrainingContractExcludedDay($this->selected_id, $id, $type, $training_contract->beginning, $training_contract->end);
            redirect(request()->header('Referer'));
        }
    }
    public function unregister($id){
        TrainingContractsExcludedDay::destroy($id);
        redirect(request()->header('Referer'));
    }

    public function addGeneralDays(){
        $training_contract = TrainingContract::find($this->selected_id);
        TrainingContractsExcludedDay::addGeneralDays($this->selected_id, $training_contract->beginning, $training_contract->end);
        redirect(request()->header('Referer'));
    }

    public function addDate(){
        $excluded_day = ExcludedDay::where('day', $this->date)->first();
        if (!$excluded_day){
            $excluded_day = ExcludedDay::create([
                'day' => $this->date
            ]);
        }
        TrainingContractsExcludedDay::create([
            'training_contract_id' => $this->selected_id,
            'excluded_day_id' => $excluded_day->id
        ]);
    }
}

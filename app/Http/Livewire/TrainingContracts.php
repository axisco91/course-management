<?php

namespace App\Http\Livewire;

use App\Exports\TrainingContractsExport;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Occupation;
use App\Models\OnLeaveType;
use App\Models\Provider;
use App\Models\Province;
use App\Models\Student;
use App\Models\TrainingContract;
use App\Models\TrainingContractStatus;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class TrainingContracts extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $number_cfa, $company_id, $student_id, $company_tutor, $company_tutor_dni, $occupation_id, $center_of_work, $province_id, $disabled, $youth_guarantee, $social_exclusion, $specialty, $professional_certificate, $beginning,
            $end, $beginning_formation, $end_formation, $formation_hours, $annually_day_hours, $bonus_hours_first_year, $bonus_hours_second_year, $excluded_days,
            $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday, $training_schedule, $working_hours, $complete_schedule,
            $on_leave_type_id, $on_leave_date, $advisor_id, $collaborator_id, $percentage_first_year, $percentage_second_year, $formative_hours_first_year, $formative_hours_second_year, $provider_id;
    public $updateMode = false;
    public $tab = 'info', $companies, $students, $provinces, $training_contract_statuses, $on_leave_types, $advisors, $collaborators, $occupations, $providers;
    public $search_company_id, $search_student_id, $search_training_contract_status_id;
    protected $listeners = [
        'changeState' => 'changeState',
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $trainingContracts = TrainingContract::getTrainingContracts($keyWord, $this->search_company_id, $this->search_student_id);

        if ($this->selected_id){

        }

        return view('livewire.training-contracts.list', [
            'trainingContracts' => $trainingContracts,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){
        $this->companies = Company::where('active', 1)->get();
        $this->students = Student::where('active', 1)->get();
        $this->occupations = Occupation::all();
        $this->provinces = Province::all();
        $this->training_contract_statuses = TrainingContractStatus::all();
        $this->on_leave_types = OnLeaveType::all();
        $this->advisors = Advisor::where('active', 1)->get();
        $this->collaborators = User::where('has_commission', 1)->get();
        $this->providers = Provider::all();
    }

    private function resetInput()
    {
        $this->number_cfa = null;
        $this->company_id = null;
        $this->student_id = null;
        $this->company_tutor = null;
        $this->company_tutor_dni = null;
        $this->occupation_id = null;
        $this->center_of_work = null;
        $this->province_id = null;
        $this->disabled = null;
        $this->youth_guarantee = null;
        $this->social_exclusion = null;
        $this->specialty = null;
        $this->professional_certificate = null;
        $this->beginning = null;
        $this->end = null;
        $this->beginning_formation = null;
        $this->end_formation = null;
        $this->formation_hours = null;
        $this->annually_day_hours = null;
        $this->bonus_hours_first_year = null;
        $this->bonus_hours_second_year = null;
        $this->excluded_days = null;
        $this->monday = null;
        $this->tuesday = null;
        $this->wednesday = null;
        $this->thursday = null;
        $this->friday = null;
        $this->saturday = null;
        $this->sunday = null;
        $this->training_schedule = null;
        $this->working_hours = null;
        $this->complete_schedule = null;
        $this->training_contract_status_id = null;
        $this->on_leave_type_id = null;
        $this->on_leave_date = null;
        $this->advisor_id = null;
        $this->collaborator_id = null;
        $this->percentage_first_year = null;
        $this->percentage_second_year = null;
        $this->formative_hours_first_year = null;
        $this->formative_hours_second_year = null;
        $this->provider_id = null;
    }

    public function general($id)
    {
        $record = TrainingContract::findOrFail($id);

        $this->selected_id = $id;
        $this->number_cfa = $record->number_cfa;
        $this->company_id = $record->company_id;
        $this->student_id = $record->student_id;
        $this->company_tutor = $record->company_tutor;
        $this->company_tutor_dni = $record->company_tutor_dni;
        $this->occupation_id = $record->occupation_id;
        $this->center_of_work = $record->center_of_work;
        $this->province_id = $record->province_id;
        $this->disabled = $record->disabled;
        $this->youth_guarantee = $record->youth_guarantee;
        $this->social_exclusion = $record->social_exclusion;
        $this->specialty = $record->specialty;
        $this->professional_certificate = $record->professional_certificate;
        $this->beginning = $record->beginning;
        $this->end = $record->end;
        $this->beginning_formation = $record->beginning_formation;
        $this->end_formation = $record->end_formation;
        $this->formation_hours = $record->formation_hours;
        $this->annually_day_hours = $record->annually_day_hours;
        $this->bonus_hours_first_year = $record->bonus_hours_first_year;
        $this->bonus_hours_second_year = $record->bonus_hours_second_year;
        $this->monday = $record->monday;
        $this->tuesday = $record->tuesday;
        $this->wednesday = $record->wednesday;
        $this->thursday = $record->thursday;
        $this->friday = $record->friday;
        $this->saturday = $record->saturday;
        $this->sunday = $record->sunday;
        $this->training_schedule = $record->training_schedule;
        $this->working_hours = $record->working_hours;
        $this->complete_schedule = $record->complete_schedule;
        $this->training_contract_status_id = $record->training_contract_status_id;
        $this->on_leave_type_id = $record->on_leave_type_id;
        $this->on_leave_date = $record->on_leave_date;
        $this->advisor_id = $record->advisor_id;
        $this->collaborator_id = $record->collaborator_id;
        $this->percentage_first_year = $record->percentage_first_year;
        $this->percentage_second_year = $record->percentage_second_year;
        $this->formative_hours_first_year = $record->formative_hours_first_year;
        $this->formative_hours_second_year = $record->formative_hours_second_year;
        $this->provider_id = $record->province_id;
    }

    public function getInfo($id){
        $this->emit('getTrainingContractInfo', $id);
        $training_contract = TrainingContract::find($id);
        $this->selected_id = $id;
    }

     public function downloadExcel(){
         $this->excelModal = false;
         return (new TrainingContractsExport($this->search_number_cfa, $this->search_company_id, $this->search_student_id, $this->search_training_contract_status_id))->download('formative_contracts.xlsx');
     }

    public function destroy($id){
        if ($id) {
            TrainingContract::destroy($id);
            return 1;
        }
    }
}

<?php

namespace App\Http\Livewire;

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

class TrainingContractsUpdate extends Component
{
    public $selected_id, $keyWord, $number_cfa, $company_id, $student_id, $company_tutor, $company_tutor_dni,  $occupation_id, $center_of_work, $province_id, $disabled, $youth_guarantee, $social_exclusion, $specialty, $professional_certificate, $beginning,
        $end, $beginning_formation, $end_formation, $formation_hours, $annually_day_hours, $bonus_hours_first_year, $bonus_hours_second_year,
        $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday, $training_schedule, $working_hours, $complete_schedule, $training_contract_status_id,
        $on_leave_type_id, $on_leave_date, $advisor_id, $collaborator_id, $percentage_first_year, $percentage_second_year, $formative_hours_first_year, $formative_hours_second_year, $provider_id;
    public $companies, $students, $provinces, $training_contract_statuses, $on_leave_types, $advisors, $collaborators, $occupations, $providers;
    protected $listeners = ['calculateFirstYearHours' => 'calculateFirstYearHours', 'calculateSecondYearHours' => 'calculateSecondYearHours'];
    public function render()
    {
        return view('livewire.training-contracts.update');
    }

    public function mount($id){
        $this->companies = Company::where('active', 1)->get();
        $this->students = Student::where('active', 1)->get();
        $this->occupations = Occupation::all();
        $this->provinces = Province::all();
        $this->training_contract_statuses = TrainingContractStatus::all();
        $this->on_leave_types = OnLeaveType::all();
        $this->advisors = Advisor::where('active', 1)->get();
        $this->collaborators = User::where('has_commission', 1)->get();
        $record = TrainingContract::findOrFail($id);
        $this->providers = Provider::all();


        $this->selected_id = $id;
        $this->number_cfa = $record->number_cfa;
        $this->company_id = $record->company_id;
        $this->student_id = $record->student_id;
        $this->company_tutor = $record->company_tutor;
        $this->center_of_work = $record->center_of_work;
        $this->province_id = $record->province_id;
        $this->disabled = $record->disabled  == 1 ? $record-> disabled : null;;
        $this->youth_guarantee = $record->youth_guarantee  == 1 ? $record-> youth_guarantee : null;;
        $this->social_exclusion = $record->social_exclusion  == 1 ? $record-> social_exclusion : null;;
        $this->specialty = $record->specialty == 1 ? $record-> specialty : null;;
        $this->professional_certificate = $record->professional_certificate == 1 ? $record-> professional_certificate : null;;
        $this->beginning = $record->beginning;
        $this->end = $record->end;
        $this->beginning_formation = $record->beginning_formation;
        $this->end_formation = $record->end_formation;
        $this->formation_hours = $record->formation_hours;
        $this->annually_day_hours = $record->annually_day_hours;
        $this->bonus_hours_first_year = $record->bonus_hours_first_year;
        $this->bonus_hours_second_year = $record->bonus_hours_second_year;
        $this->monday = $record->monday == 1 ? $record-> monday : null;;
        $this->tuesday = $record->tuesday == 1 ? $record-> tuesday : null;;
        $this->wednesday = $record->wednesday == 1 ? $record-> wednesday : null;;
        $this->thursday = $record->thursday == 1 ? $record-> thursday : null;;
        $this->friday = $record->friday == 1 ? $record-> friday : null;;
        $this->saturday = $record->saturday == 1 ? $record-> saturday : null;;
        $this->sunday = $record->sunday == 1 ? $record-> sunday : null;;
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
        $this->provider_id = $record->provider_id;
    }

    public function update()
    {
        $this->validate([
            'number_cfa' => 'required',
            'company_id' => 'required',
            'student_id' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'number_cfa' => $this-> number_cfa,
                'company_id' => $this->company_id,
                'student_id' => $this->student_id,
                'company_tutor' => $this->company_tutor,
                'company_tutor_dni' => $this->company_tutor_dni,
                'occupation_id' => $this->occupation_id,
                'center_of_work' => $this->center_of_work,
                'province_id' => $this->province_id,
                'disabled' => $this->disabled,
                'youth_guarantee' => $this->youth_guarantee,
                'social_exclusion' => $this->social_exclusion,
                'specialty' => $this->specialty,
                'professional_certificate' => $this->professional_certificate,
                'beginning' => $this->beginning,
                'end' => $this->end,
                'beginning_formation' => $this->beginning_formation,
                'end_formation' => $this->end_formation,
                'formation_hours' => $this->formation_hours,
                'annually_day_hours' => $this->annually_day_hours,
                'bonus_hours_first_year' => $this->bonus_hours_first_year,
                'bonus_hours_second_year' => $this->bonus_hours_second_year,
                'monday' => $this->monday,
                'tuesday' => $this->tuesday,
                'wednesday' => $this->wednesday,
                'thursday' => $this->thursday,
                'friday' => $this->friday,
                'saturday' => $this->saturday,
                'sunday' => $this->sunday,
                'training_schedule' => $this->training_schedule,
                'working_hours' => $this->working_hours,
                'complete_schedule' => $this->complete_schedule,
                'training_contract_status_id' => $this->training_contract_status_id,
                'on_leave_type_id' => $this->on_leave_type_id,
                'on_leave_date' => $this->on_leave_date,
                'advisor_id' => $this->advisor_id,
                'collaborator_id' => $this->collaborator_id,
                'percentage_first_year' => $this->percentage_first_year,
                'percentage_second_year' => $this->percentage_second_year,
                'formative_hours_first_year' => $this->formative_hours_first_year,
                'formative_hours_second_year' => $this->formative_hours_second_year,
                'provider_id' => $this->provider_id,
            ];

            TrainingContract::updateTrainingContract($this->selected_id, $data);
            session()->flash('message', 'Contrato formativo actualizada con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function calculate(){
        $percentage = $this->percentage_first_year / 100;
        $this->bonus_hours_first_year = $this->annually_day_hours * $percentage;
        $percentage = $this->percentage_second_year / 100;
        $this->bonus_hours_second_year = $this->annually_day_hours * $percentage;
    }
}

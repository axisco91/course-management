<?php

namespace App\Http\Livewire;

use App\Models\Advisor;
use App\Models\Company;
use App\Models\ExcludedDay;
use App\Models\Occupation;
use App\Models\OnLeaveType;
use App\Models\Province;
use App\Models\Student;
use App\Models\TrainingContract;
use App\Models\TrainingContractStatus;
use App\Models\User;
use Livewire\Component;

class TrainingContractsUpdate extends Component
{
    public $selected_id, $keyWord, $number_cfa, $company_id, $student_id, $company_tutor, $company_tutor_dni,  $occupation_id, $center_of_work, $province_id, $disabled, $youth_guarantee, $social_exclusion, $specialty, $professional_certificate, $beginning,
        $end, $beginning_formation, $end_formation, $formation_hours, $annually_day_hours, $bonus_hours_first_year, $bonus_hours_second_year, $excluded_day_id,
        $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday, $training_schedule, $working_hours, $complete_schedule, $training_contract_status_id,
        $on_leave_type_id, $on_leave_date, $advisor_id, $collaborator_id;
    public $companies, $students, $provinces, $training_contract_statuses, $on_leave_types, $advisors, $collaborators, $occupations, $excluded_days;

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
        $this->excluded_days = ExcludedDay::all();
        $record = TrainingContract::findOrFail($id);

        $this->selected_id = $id;
        $this->number_cfa = $record->number_cfa;
        $this->company_id = $record->company_id;
        $this->student_id = $record->student_id;
        $this->company_tutor = $record->company_tutor;
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
        $this->excluded_day_id = $record->excludedDays()->get()->pluck('id')->toArray();
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
                'excluded_days' => $this->excluded_days,
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
                'collaborator_id' => $this->collaborator_id
            ];

            TrainingContract::updateTrainingContract($this->selected_id, $data);
            session()->flash('message', 'Contrato formativo actualizada con exito.');
            $this->emit('toastr', 'success');
        }
    }
}

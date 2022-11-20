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

class TrainingContractsCreate extends Component
{

    public $selected_id, $keyWord, $number_cfa, $company_id, $student_id, $company_tutor, $company_tutor_dni, $occupation_id, $center_of_work, $province_id, $disabled, $youth_guarantee, $social_exclusion, $specialty, $professional_certificate, $beginning,
        $end, $beginning_formation, $end_formation, $formation_hours, $annually_day_hours, $bonus_hours_first_year, $bonus_hours_second_year, $excluded_day_id,
        $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday, $training_schedule, $working_hours, $complete_schedule, $training_contract_status_id,
        $on_leave_type_id, $on_leave_date, $advisor_id, $collaborator_id;
    public $companies, $students, $provinces, $training_contract_statuses, $on_leave_types, $advisors, $collaborators, $occupations, $excluded_days;

    public function render()
    {
        return view('livewire.training-contracts.create');
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
        $this->excluded_days = ExcludedDay::all();
    }

    public function store()
    {
        $this->validate([
            'number_cfa' => 'required',
            'company_id' => 'required',
            'student_id' => 'required',
            'beginning' => 'required',
            'end' => 'required',
            'beginning_formation' => 'required',
            'end_formation' => 'required'
        ]);

        $training_contract = TrainingContract::create([
            'number_cfa' => $this-> number_cfa,
            'company_id' => $this->company_id,
            'student_id' => $this->student_id,
            'company_tutor' => $this->company_tutor,
            'company_tutor_dni' => $this->company_tutor_dni,
            'occupation_id' => $this->occupation_id,
            'center_of_work' => $this->center_of_work,
            'province_id' => $this->province_id,
            'disabled' => $this->disabled == true ? 1 : 0,
            'youth_guarantee' => $this->youth_guarantee == true ? 1 : 0,
            'social_exclusion' => $this->social_exclusion == true ? 1 : 0,
            'specialty' => $this->specialty == true ? 1 : 0,
            'professional_certificate' => $this->professional_certificate == true ? 1 : 0,
            'beginning' => $this->beginning,
            'end' => $this->end,
            'beginning_formation' => $this->beginning_formation,
            'end_formation' => $this->end_formation,
            'formation_hours' => $this->formation_hours,
            'annually_day_hours' => $this->annually_day_hours,
            'bonus_hours_first_year' => $this->bonus_hours_first_year,
            'bonus_hours_second_year' => $this->bonus_hours_second_year,
            'monday' => $this-> monday == true ? 1 : 0,
            'tuesday' => $this-> tuesday == true ? 1 : 0,
            'wednesday' => $this-> wednesday == true ? 1 : 0,
            'thursday' => $this-> thursday == true ? 1 : 0,
            'friday' => $this-> friday == true ? 1 : 0,
            'saturday' => $this-> saturday == true ? 1 : 0,
            'sunday' => $this-> sunday == true ? 1 : 0,
            'training_schedule' => $this->training_schedule,
            'working_hours' => $this->working_hours,
            'complete_schedule' => $this->complete_schedule,
            'training_contract_status_id' => $this->training_contract_status_id,
            'on_leave_type_id' => $this->on_leave_type_id,
            'on_leave_date' => $this->on_leave_date,
            'advisor_id' => $this->advisor_id,
            'collaborator_id' => $this->collaborator_id
        ]);
        $training_contract->excludedDays()->sync($this->excluded_day_id);
		session()->flash('message', 'Contrato formativo creado con exito.');
        return redirect('training_contracts/edit'.$training_contract->id);
    }
}

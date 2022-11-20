<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContract extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function excludedDays(){
        return $this->belongsToMany(ExcludedDay::class, 'training_contracts_excluded_days', 'training_contract_id', 'excluded_day_id');
    }

    public function getTrainingContracts($keyWord, $company_id, $student_id){
        $trainingContract = TrainingContract::select('training_contracts.*', 'companies.name as company_name', 'students.name as student_name', 'students.surname as student_surname',
        'training_contract_statuses.name as training_contract_status')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'training_contracts.province_id')
            ->leftjoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id');

        $trainingContract = $trainingContract->where(function ($query) use ($keyWord){
            $query->orWhere('training_contracts.number_cfa', 'LIKE', $keyWord)
                ->orWhere('companies.name', 'LIKE', $keyWord)
                ->orWhere('students.name', 'LIKE', $keyWord)
                ->orWhere('training_contracts.company_tutor', 'LIKE', $keyWord)
                ->orWhere('training_contracts.center_of_work', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('beginning', 'LIKE', $keyWord)
                ->orWhere('end', 'LIKE', $keyWord)
                ->orWhere('beginning_formation', 'LIKE', $keyWord)
                ->orWhere('end_formation', 'LIKE', $keyWord)
                ->orWhere('formation_hours', 'LIKE', $keyWord)
                ->orWhere('annually_day_hours', 'LIKE', $keyWord)
                ->orWhere('bonus_hours_first_year', 'LIKE', $keyWord)
                ->orWhere('bonus_hours_second_year', 'LIKE', $keyWord)
                ->orWhere('training_schedule', 'LIKE', $keyWord)
                ->orWhere('working_hours', 'LIKE', $keyWord)
                ->orWhere('complete_schedule', 'LIKE', $keyWord)
                ->orWhere('training_contract_statuses.name', 'LIKE', $keyWord)
                ->orWhere('on_leave_date', 'LIKE', $keyWord);
        });

        if ($company_id){
            $trainingContract = $trainingContract->Where('training_contracts.company_id', $company_id);
        }
        if ($student_id){
            $trainingContract = $trainingContract->Where('training_contracts.student_id', $student_id);
        }

        $trainingContract = $trainingContract->orderby('id', 'asc')
            ->paginate(10);
        return $trainingContract;
    }

    public function createTrainingContract($data){
        $training_contract = TrainingContract::create([
            'number_cfa' => $data['number_cfa'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'province_id' => $data['province_id'],
            'worker_status' => $data['worker_status'],
            'contract_type' => $data['contract_type'],
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'beginning_formation' => $data['beginning_formation'],
            'end_formation' => $data['end_formation'],
            'formation_hours' => $data['formation_hours'],
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' => $data['bonus_hours_first_year'],
            'bonus_hours_second_year' => $data['bonus_hours_second_year'],
            'monday' => $data['monday'],
            'tuesday' => $data['tuesday'],
            'wednesday' => $data['wednesday'],
            'thursday' => $data['thursday'],
            'friday' => $data['friday'],
            'saturday' => $data['saturday'],
            'sunday' => $data['sunday'],
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $data['on_leave_date'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
        ]);
        $training_contract->excludedDays()->sync($data['excluded_day_id']);
    }

    public function updateTrainingContract($id, $data){
        $training_contract = TrainingContract::find($id);
        $training_contract->update([
            'number_cfa' => $data['number_cfa'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'province_id' => $data['province_id'],
            'disabled' => $data['disabled'] == true ? 1 : 0,
            'youth_guarantee' => $data['youth_guarantee'] == true ? 1 : 0,
            'social_exclusion' => $data['social_exclusion'] == true ? 1 : 0,
            'specialty' => $data['specialty'] == true ? 1 : 0,
            'professional_certificate' => $data['professional_certificate'] == true ? 1 : 0,
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'beginning_formation' => $data['beginning_formation'],
            'end_formation' => $data['end_formation'],
            'formation_hours' => $data['formation_hours'],
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' => $data['bonus_hours_first_year'],
            'bonus_hours_second_year' => $data['bonus_hours_second_year'],
            'monday' => $data['monday'] == true ? 1 : 0,
            'tuesday' => $data['tuesday'] == true ? 1 : 0,
            'wednesday' => $data['wednesday'] == true ? 1 : 0,
            'thursday' => $data['thursday'] == true ? 1 : 0,
            'friday' => $data['friday'] == true ? 1 : 0,
            'saturday' => $data['saturday'] == true ? 1 : 0,
            'sunday' => $data['sunday'] == true ? 1 : 0,
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $data['on_leave_date'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
        ]);

        return $training_contract;
    }

    public function updateHours($exam_difference, $tutoring_difference, $teletraining_difference){
        $total_hours = $this->total_hours + $exam_difference + $tutoring_difference + $teletraining_difference;
        $this->update([
            'total_hours' => $total_hours
        ]);
    }
}

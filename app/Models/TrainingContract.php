<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingContract extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function excludedDays(){
        return $this->belongsToMany(ExcludedDay::class, 'training_contracts_excluded_days', 'training_contract_id', 'excluded_day_id');
    }

    public static function getTrainingContracts(){
        $trainingContract = TrainingContract::select('training_contracts.*',
            'companies.name as company_name',
            'students.name as student_name',
            'students.surname as student_surname',
            'training_contract_statuses.name as training_contract_status',
            'providers.name as provider',
            'provinces.name as province',
            'on_leave_types.name as on_leave_type',
            'advisors.name as advisor',
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"),
            DB::raw("CONCAT(students.name,' ',students.surname) as student"),
            'occupations.name as occupation')
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'training_contracts.province_id')
            ->leftjoin('providers', 'providers.id', '=', 'training_contracts.provider_id')
            ->leftjoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
            ->leftjoin('on_leave_types', 'on_leave_types.id', '=', 'training_contracts.on_leave_type_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'training_contracts.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'training_contracts.collaborator_id')
            ->leftjoin('occupations', 'occupations.id', '=', 'training_contracts.occupation_id')
            ->orderby('beginning', 'desc')
            ->get();
        return $trainingContract;
    }

    public static function createTrainingContract($data){
        $training_contract = TrainingContract::create([
            'number_cfa' => $data['number_cfa'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'province_id' => $data['province_id'],
            'beginning' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'end' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'beginning_formation' => $data['beginning_formation'] ? Carbon::createFromFormat('d-m-Y', $data['beginning_formation'])->format('Y-m-d') : null,
            'end_formation' => $data['end_formation'] ? Carbon::createFromFormat('d-m-Y', $data['end_formation'])->format('Y-m-d') : null,
            'formation_hours' => $data['formation_hours'],
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' => $data['bonus_hours_first_year'] ? $data['bonus_hours_first_year'] : 0,
            'bonus_hours_second_year' => $data['bonus_hours_second_year'] ? $data['bonus_hours_second_year'] : 0,
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $data['on_leave_date'] ? Carbon::createFromFormat('d-m-Y', $data['on_leave_date'])->format('Y-m-d') : null,
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'percentage_first_year' => $data['percentage_first_year'],
            'percentage_second_year' => $data['percentage_second_year'],
            'formative_hours_first_year' => $data['formative_hours_first_year'] ? $data['formative_hours_first_year'] : 0,
            'formative_hours_second_year' => $data['formative_hours_second_year'] ? $data['formative_hours_second_year'] : 0,
            'provider_id' => $data['provider_id'],
        ]);
        $training_contract->excludedDays()->sync($data['excluded_day_id']);

        if ($data['disabled'] !== '') {
            $training_contract->update([
                'disabled' => $data['disabled']
            ]);
        }
        if ($data['youth_guarantee'] !== '') {
            $training_contract->update([
                'youth_guarantee' => $data['youth_guarantee']
            ]);
        } if ($data['social_exclusion'] !== '') {
            $training_contract->update([
                'social_exclusion' => $data['social_exclusion']
            ]);
        }
        if ($data['specialty'] !== '') {
            $training_contract->update([
                'specialty' => $data['specialty']
            ]);
        }
        if ($data['professional_certificate'] !== '') {
            $training_contract->update([
                'professional_certificate' => $data['professional_certificate']
            ]);
        }
        if ($data['monday'] !== '') {
            $training_contract->update([
                'monday' => $data['monday']
            ]);
        }
        if ($data['tuesday'] !== '') {
            $training_contract->update([
                'tuesday' => $data['tuesday']
            ]);
        }
        if ($data['wednesday'] !== '') {
            $training_contract->update([
                'wednesday' => $data['wednesday']
            ]);
        }
        if ($data['thursday'] !== '') {
            $training_contract->update([
                'thursday' => $data['thursday']
            ]);
        }
        if ($data['friday'] !== '') {
            $training_contract->update([
                'friday' => $data['friday']
            ]);
        }
        if ($data['saturday'] !== '') {
            $training_contract->update([
                'saturday' => $data['saturday']
            ]);
        }
        if ($data['sunday'] !== '') {
            $training_contract->update([
                'sunday' => $data['sunday']
            ]);
        }

        return $training_contract;
    }

    public static function updateTrainingContract($id, $data){
        $training_contract = TrainingContract::find($id);
        $training_contract->update([
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'company_tutor' => $data['company_tutor'],
            'company_tutor_dni' => $data['company_tutor_dni'],
            'occupation_id' => $data['occupation_id'],
            'center_of_work' => $data['center_of_work'],
            'province_id' => $data['province_id'],
            'beginning' => $data['beginning'] ? Carbon::createFromFormat('d-m-Y', $data['beginning'])->format('Y-m-d') : null,
            'end' => $data['end'] ? Carbon::createFromFormat('d-m-Y', $data['end'])->format('Y-m-d') : null,
            'beginning_formation' => $data['beginning_formation'] ? Carbon::createFromFormat('d-m-Y', $data['beginning_formation'])->format('Y-m-d') : null,
            'end_formation' => $data['end_formation'] ? Carbon::createFromFormat('d-m-Y', $data['end_formation'])->format('Y-m-d') : null,
            'formation_hours' => $data['formation_hours'],
            'annually_day_hours' => $data['annually_day_hours'],
            'bonus_hours_first_year' => $data['bonus_hours_first_year'] ? $data['bonus_hours_first_year'] : 0,
            'bonus_hours_second_year' => $data['bonus_hours_second_year'] ? $data['bonus_hours_second_year'] : 0,
            'training_schedule' => $data['training_schedule'],
            'working_hours' => $data['working_hours'],
            'complete_schedule' => $data['complete_schedule'],
            'training_contract_status_id' => $data['training_contract_status_id'],
            'on_leave_type_id' => $data['on_leave_type_id'],
            'on_leave_date' => $data['on_leave_date'] ? Carbon::createFromFormat('d-m-Y', $data['on_leave_date'])->format('Y-m-d') : null,
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'percentage_first_year' => $data['percentage_first_year'],
            'percentage_second_year' => $data['percentage_second_year'],
            'formative_hours_first_year' => $data['formative_hours_first_year'] ? $data['formative_hours_first_year'] : 0,
            'formative_hours_second_year' => $data['formative_hours_second_year'] ? $data['formative_hours_second_year'] : 0,
            'provider_id' => $data['provider_id'],
        ]);

        if ($data['disabled'] !== '') {
            $training_contract->update([
                'disabled' => $data['disabled']
            ]);
        }
        if ($data['youth_guarantee'] !== '') {
            $training_contract->update([
                'youth_guarantee' => $data['youth_guarantee']
            ]);
        } if ($data['social_exclusion'] !== '') {
            $training_contract->update([
                'social_exclusion' => $data['social_exclusion']
            ]);
        }
        if ($data['specialty'] !== '') {
            $training_contract->update([
                'specialty' => $data['specialty']
            ]);
        }
        if ($data['professional_certificate'] !== '') {
            $training_contract->update([
                'professional_certificate' => $data['professional_certificate']
            ]);
        }
        if ($data['monday'] !== '') {
            $training_contract->update([
                'monday' => $data['monday']
            ]);
        }
        if ($data['tuesday'] !== '') {
            $training_contract->update([
                'tuesday' => $data['tuesday']
            ]);
        }
        if ($data['wednesday'] !== '') {
            $training_contract->update([
                'wednesday' => $data['wednesday']
            ]);
        }
        if ($data['thursday'] !== '') {
            $training_contract->update([
                'thursday' => $data['thursday']
            ]);
        }
        if ($data['friday'] !== '') {
            $training_contract->update([
                'friday' => $data['friday']
            ]);
        }
        if ($data['saturday'] !== '') {
            $training_contract->update([
                'saturday' => $data['saturday']
            ]);
        }
        if ($data['sunday'] !== '') {
            $training_contract->update([
                'sunday' => $data['sunday']
            ]);
        }

        return $training_contract;
    }

    public function updateHours($exam_difference, $tutoring_difference, $teletraining_difference){
        $total_hours = $this->total_hours + $exam_difference + $tutoring_difference + $teletraining_difference;
        $this->update([
            'total_hours' => $total_hours
        ]);
    }

    public static function calculateTotalHours($id){
        $elements = TrainingContractElement::where('training_contract_id', $id)->get();
        $hours = 0;
        if ($elements){
            foreach ($elements as $element){
                if ($element->certification_id){
                    $certification = Certification::find($element->certification_id);
                    if ($certification){
                        $hours = $hours + $certification->total_hours;
                    }
                } else if($element->training_action_id){
                    $training_action = TrainingAction::find($element->triaining_action_id);
                    if ($training_action){
                        $hours = $hours + $training_action->total_hours;
                    }
                }
            }
        }
        $training_contract = TrainingContract::find($id);
        $training_contract->update([
            'total_hours' => $hours
        ]);
        return $hours;
    }
}

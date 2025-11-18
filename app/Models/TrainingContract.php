<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\TrainingContractService;

class TrainingContract extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function excludedDays()
    {
        return $this->belongsToMany(ExcludedDayType::class, 'training_contracts_excluded_days', 'training_contract_id', 'excluded_day_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function trainingContractElements()
    {
        return $this->hasMany(TrainingContractElement::class);
    }

    public function trainingContractFestivals()
    {
        return $this->hasMany(TrainingContractFestival::class);
    }

    public function trainingContractExcludedDays()
    {
        return $this->hasMany(TrainingContractsExcludedDay::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function applicableAgreement()
    {
        return $this->belongsTo(ApplicableAgreement::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function scopeGetTrainingContracts($query, $mainCompanyId)
    {
        return $query->select(
            'training_contracts.*',
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
            'occupations.name as occupation'
        )
            ->leftjoin('companies', 'companies.id', '=', 'training_contracts.company_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'training_contracts.province_id')
            ->leftjoin('providers', 'providers.id', '=', 'training_contracts.provider_id')
            ->leftjoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
            ->leftjoin('on_leave_types', 'on_leave_types.id', '=', 'training_contracts.on_leave_type_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'training_contracts.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'training_contracts.collaborator_id')
            ->leftjoin('occupations', 'occupations.id', '=', 'training_contracts.occupation_id')
            ->where('training_contracts.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('training_contracts.main_company_id', $mainCompanyId);
    }

    public function calculateHours()
    {
        $service = app(TrainingContractService::class);
        return $service->calculateHours($this);
    }

    public function calculateMonthlyFormationHours()
    {
        $service = app(TrainingContractService::class);
        return $service->calculateMonthlyFormationHours($this->id);
    }

    public function updateContractDates($total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $daily_hours_1, $daily_hours_2)
    {
        $service = app(TrainingContractService::class);
        return $service->updateContractDates($this, $total_hours, $bonus_hours_first_year, $bonus_hours_second_year, $daily_hours_1, $daily_hours_2);
    }

    public static function createWithService($data)
    {
        $service = app(TrainingContractService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TrainingContractService::class);
        return $service->update($this, $data);
    }

    public function updateDocumentClause($data){
        $service = app(TrainingContractService::class);
        return $service->updateDocumentClause($this, $data);
    }
}

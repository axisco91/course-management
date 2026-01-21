<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    public function trainingContractStatus()
    {
        return $this->belongsTo(TrainingContractStatus::class, 'training_contract_status_id');
    }

    public function onLeaveType()
    {
        return $this->belongsTo(OnLeaveType::class, 'on_leave_type_id');
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'advisor_id');
    }

    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }


    public function scopeGetTrainingContracts($query, $mainCompanyId)
    {
        return $query
            ->select('training_contracts.*')
            ->with([
                'company:id,name',
                'student:id,name,surname',
                'trainingContractStatus:id,name',
                'provider:id,name',
                'province:id,name',
                'onLeaveType:id,name',
                'advisor:id,name',
                'collaborator:id,name,surname',
                'occupation:id,name',
            ])
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

<?php

namespace App\Models;

use App\Services\TrainingContractIncidenceService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainingContractIncidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'affair',
        'notes',
        'incidence_type_id',
        'training_contract_id',
        'user_id',
        'main_company_id'
    ];

    public function scopeGetTrainingContractIncidence($query, $trainingContractId, $mainCompanyId)
    {
        return $query
            ->select(
                'training_contract_incidences.*',
                'incidence_types.name as incidence_type',
                'users.name as user_name',
                'users.surname as user_surname',
                DB::raw("CONCAT(users.name, ' ', users.surname) as user"),
                DB::raw("DATE_FORMAT(training_contract_incidences.created_at, '%d/%m/%Y') as created")
            )
            ->leftJoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftJoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_incidences.training_contract_id', $trainingContractId)
            ->where('training_contract_incidences.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('training_contract_incidences.main_company_id', $mainCompanyId);
    }


    public static function createWithService($data)
    {
        $service = app(TrainingContractIncidenceService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TrainingContractIncidenceService::class);
        return $service->update($this, $data);
    }
}

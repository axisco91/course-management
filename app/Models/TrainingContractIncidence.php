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

    public static function getTrainingContractIncidences($id, $mainCompanyId){
        $trainingContract_incidences = TrainingContractIncidence::select('training_contract_incidences.*',
            'incidence_types.name as incidence_type',
            'users.name as user_name', 'users.surname as user_surname',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_id', $id)
            ->where('training_contract_incidences.main_company_id', $mainCompanyId)
            ->get();
        foreach ($trainingContract_incidences as $trainingContract_incidence) {
            $trainingContract_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $trainingContract_incidence['created_at'])->format('d/m/Y');
        }
        return $trainingContract_incidences;
    }

    public static function getTrainingContractIncidence($id, $mainCompanyId){
        $trainingContract_incidence = TrainingContractIncidence::select('training_contract_incidences.*',
            'incidence_types.name as incidence_type',
            'users.name as user_name', 'users.surname as user_surname',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_incidences.id', $id)
            ->where('training_contract_incidences.main_company_id', $mainCompanyId)
            ->first();
        $trainingContract_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $trainingContract_incidence['created_at'])->format('d/m/Y');
        return $trainingContract_incidence;
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

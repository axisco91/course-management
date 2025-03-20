<?php

namespace App\Models;

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
    ];

    public static function getTrainingContractIncidences($id){
        $trainingContract_incidences = TrainingContractIncidence::select('training_contract_incidences.*',
            'incidence_types.name as incidence_type',
            'users.name as user_name', 'users.surname as user_surname',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_id', $id)->get();
        foreach ($trainingContract_incidences as $trainingContract_incidence) {
            $trainingContract_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $trainingContract_incidence['created_at'])->format('d/m/Y');
        }
        return $trainingContract_incidences;
    }

    public static function getTrainingContractIncidence($id){
        $trainingContract_incidence = TrainingContractIncidence::select('training_contract_incidences.*',
            'incidence_types.name as incidence_type',
            'users.name as user_name', 'users.surname as user_surname',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_incidences.id', $id)->first();
        $trainingContract_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $trainingContract_incidence['created_at'])->format('d/m/Y');
        return $trainingContract_incidence;
    }

    public static function createTrainingContractIncidence($data){
        $trainingContract_incidence = TrainingContractIncidence::create([
               'affair' => $data['affair'],
                'incidence_type_id' => $data['incidence_type_id'],
                'notes' => $data['notes'],
                'training_contract_id' => $data['training_contract_id'],
                'user_id' => $data['user_id']
        ]
        );

        return $trainingContract_incidence;
    }

    public static function updateTrainingContractIncidence($id, $data){
        $trainingContract_incidence = TrainingContractIncidence::find($id);
        $trainingContract_incidence->update([
            'affair' => $data['affair'],
            'incidence_type_id' => $data['incidence_type_id'],
            'notes' => $data['notes'],
            'training_contract_id' => $data['training_contract_id'],
            'user_id' => $data['user_id']
        ]);

        return $trainingContract_incidence;
    }
}

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
        $training_contract_incidences = TrainingContractIncidence::select('training_contract_incidences.*',
            'incidence_types.name as incidence_type',
            'users.name as user_name', 'users.surname as user_surname',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_id', $id)->get();
        foreach ($training_contract_incidences as $training_contract_incidence) {
            $training_contract_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $training_contract_incidence['created_at'])->format('d/m/Y');
        }
        return $training_contract_incidences;
    }

    public static function getTrainingContractIncidence($id){
        $training_contract_incidence = TrainingContractIncidence::select('training_contract_incidences.*',
            'incidence_types.name as incidence_type',
            'users.name as user_name', 'users.surname as user_surname',
            DB::raw("CONCAT(users.name,' ',users.surname) as user"))
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_incidences.id', $id)->first();
        $training_contract_incidence['created'] = Carbon::createFromFormat('Y-m-d H:i:s', $training_contract_incidence['created_at'])->format('d/m/Y');
        return $training_contract_incidence;
    }

    public static function createTrainingContractIncidence($data){
        $training_contract_incidence = TrainingContractIncidence::create([
               'affair' => $data['affair'],
                'incidence_type_id' => $data['incidence_type_id'],
                'notes' => $data['notes'],
                'training_contract_id' => $data['training_contract_id'],
                'user_id' => $data['user_id']
        ]
        );

        return $training_contract_incidence;
    }

    public static function updateTrainingContractIncidence($id, $data){
        $training_contract_incidence = TrainingContractIncidence::find($id);
        $training_contract_incidence->update([
            'affair' => $data['affair'],
            'incidence_type_id' => $data['incidence_type_id'],
            'notes' => $data['notes'],
            'training_contract_id' => $data['training_contract_id'],
            'user_id' => $data['user_id']
        ]);

        return $training_contract_incidence;
    }
}

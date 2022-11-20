<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getTrainingContractIncidences($keyWord, $training_contract_id){
        $training_contract_incidences = TrainingContractIncidence::select('training_contract_incidences.*', 'incidence_types.name as incidence_type',
        'users.name as user_name', 'users.surname as user_surname')
            ->leftjoin('incidence_types', 'incidence_types.id', '=', 'training_contract_incidences.incidence_type_id')
            ->leftjoin('users', 'users.id', '=', 'training_contract_incidences.user_id')
            ->where('training_contract_id', $training_contract_id)->paginate(10);

        return $training_contract_incidences;
    }

    public static function createTrainingContractIncidence($data){
        $training_contract_incidence = TrainingContractIncidence::create(
            $data
        );

        return $training_contract_incidence;
    }

    public static function updateTrainingContractIncidence($id, $data){
        $training_contract_incidence = TrainingContractIncidence::find($id);
        $training_contract_incidence->update(
            $data
        );

        return $training_contract_incidence;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public static function getTrainingContractStatuses(){
        $statuses = TrainingContractStatus::select('*', 'id as value', 'name as label')
            ->get();
        foreach ($statuses as $status){
            $contract = TrainingContract::where('training_contract_status_id', $status['id'])->first();
            if ($contract){
                $status['used'] = true;
            } else{
                $status['used'] = false;
            }
        }
        return $statuses;
    }

    public static function getTrainingContractStatus($id){
        $status = TrainingContractStatus::select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->first();
        $contract = TrainingContract::where('training_contract_status_id', $status['id'])->first();
        if ($contract){
            $status['used'] = true;
        } else{
            $status['used'] = false;
        }
        return $status;
    }

    public static function createTrainingContractStatus($data){
        $contract = TrainingContractStatus::create([
            'name' => $data['name']
        ]);

        return $contract;
    }

    public static function updateTrainingContractStatus($id, $data){
        $contract = TrainingContractStatus::find($id);
        $contract->update([
            'name' => $data['name']
        ]);

        return $contract;
    }

}

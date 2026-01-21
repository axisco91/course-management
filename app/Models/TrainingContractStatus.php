<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function scopeGetTrainingContractStatus($query)
    {
        return $query
            ->select(
                'training_contract_statuses.*',
                'training_contract_statuses.id as value',
                'training_contract_statuses.name as label'
            )
            ->leftJoin(
                'training_contracts',
                'training_contracts.training_contract_status_id',
                '=',
                'training_contract_statuses.id'
            )
            ->selectRaw('CASE WHEN training_contracts.id IS NULL THEN false ELSE true END as used')
            ->groupBy('training_contract_statuses.id');
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

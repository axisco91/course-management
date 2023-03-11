<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public static function getTrainingContractStatus(){
        $contract = TrainingContractStatus::select('*', 'id as value', 'name as label')
            ->get();
        return $contract;
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

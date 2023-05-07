<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractBonus extends Model
{
    use HasFactory;

    protected $fillable = ['training_contract_id',
        'advisor_id',
        'collaborator_id',
        'users',
        'month',
        'year',
        'start',
        'end',
        'amount',
        'invoiced',
        'hours'
    ];

    public static function getBonuses($id){
        $bonuses = TrainingContractBonus::where('training_contract_id', $id)->get();
        foreach ($bonuses as $bonus) {
            switch ($bonus['month']) {
                case 1:
                    $bonus['month_name'] = 'Enero';
                    break;
                case 2:
                    $bonus['month_name'] = 'Febrero';
                    break;
                case 3:
                    $bonus['month_name'] = 'Marzo';
                    break;
                case 4:
                    $bonus['month_name'] = 'Abril';
                    break;
                case 5:
                    $bonus['month_name'] = 'Mayo';
                    break;
                case 6:
                    $bonus['month_name'] = 'Junio';
                    break;
                case 7:
                    $bonus['month_name'] = 'Julio';
                    break;
                case 8:
                    $bonus['month_name'] = 'Agosto';
                    break;
                case 9:
                    $bonus['month_name'] = 'Septiembre';
                    break;
                case 10:
                    $bonus['month_name'] = 'Octubre';
                    break;
                case 11:
                    $bonus['month_name'] = 'Nomviebre';
                    break;
                case 12:
                    $bonus['month_name'] = 'Diciembre';
                    break;
            }
        }
        return $bonuses;
    }

    public static function getBonus($id){
        $bonus = TrainingContractBonus::where('id', $id)->first();
        switch ($bonus['month']) {
            case 1:
                $bonus['month_name'] = 'Enero';
                break;
            case 2:
                $bonus['month_name'] = 'Febrero';
                break;
            case 3:
                $bonus['month_name'] = 'Marzo';
                break;
            case 4:
                $bonus['month_name'] = 'Abril';
                break;
            case 5:
                $bonus['month_name'] = 'Mayo';
                break;
            case 6:
                $bonus['month_name'] = 'Junio';
                break;
            case 7:
                $bonus['month_name'] = 'Julio';
                break;
            case 8:
                $bonus['month_name'] = 'Agosto';
                break;
            case 9:
                $bonus['month_name'] = 'Septiembre';
                break;
            case 10:
                $bonus['month_name'] = 'Octubre';
                break;
            case 11:
                $bonus['month_name'] = 'Nomviebre';
                break;
            case 12:
                $bonus['month_name'] = 'Diciembre';
                break;
        }
        return $bonus;
    }

    public static function createBonus($data){
        $training_contract = TrainingContract::find($data['training_contract_id']);
        $bonus = TrainingContractBonus::create([
            'training_contract_id' => $training_contract->id,
            'advisor_id' => $training_contract->advisor_id,
            'collaborator_id' => $training_contract->collaborator_id,
            'month' => $data['month'],
            'year' => $data['year'],
            'start' => $data['start'],
            'end' => $data['end'],
            'amount' => $data['amount'],
            'invoiced' => $data['invoiced'],
            'hours' => $data['hours']
        ]);
        return $bonus;
    }

    public static function updateBonus($id, $data){
        $training_contract = TrainingContract::find($data['training_contract_id']);
        $bonus = TrainingContractBonus::find($id);
        $bonus->update([
            'advisor_id' => $training_contract->advisor_id,
            'collaborator_id' => $training_contract->collaborator_id,
            'month' => $data['month'],
            'year' => $data['year'],
            'start' => $data['start'],
            'end' => $data['end'],
            'amount' => $data['amount'],
            'invoiced' => $data['invoiced'],
            'hours' => $data['hours']
        ]);

        return $bonus;
    }

}

<?php

namespace App\Models;

use App\Services\TrainingContractBonusService;
use Carbon\Carbon;
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
        'hours',
        'main_company_id'
    ];

    public static function getBonuses($id, $mainCompanyId){
        $bonuses = TrainingContractBonus::where('training_contract_id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->orderBy('start', 'asc')
            ->get();
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
                    $bonus['month_name'] = 'Noviembre';
                    break;
                case 12:
                    $bonus['month_name'] = 'Diciembre';
                    break;
            }
        }
        return $bonuses;
    }

    public static function getBonus($id, $mainCompanyId){
        $bonus = TrainingContractBonus::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

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
                $bonus['month_name'] = 'Noviembre';
                break;
            case 12:
                $bonus['month_name'] = 'Diciembre';
                break;
        }
        return $bonus;
    }

    public static function createBonus($data){
        $trainingContract = TrainingContract::find($data['training_contract_id']);
        $bonus = TrainingContractBonus::create([
            'training_contract_id' => $trainingContract->id,
            'advisor_id' => $trainingContract->advisor_id,
            'collaborator_id' => $trainingContract->collaborator_id,
            'month' => $data['month'],
            'year' => $data['year'],
            'start' => Carbon::parse($data['start'])->toDateString(),
            'end' => Carbon::parse($data['end'])->toDateString(),
            'amount' => $data['amount'],
            'invoiced' => $data['invoiced'],
            'hours' => $data['hours']
        ]);
        return $bonus;
    }

    public static function updateBonus($id, $data){
        $trainingContract = TrainingContract::find($data['training_contract_id']);
        $bonus = TrainingContractBonus::find($id);
        $bonus->update([
            'advisor_id' => $trainingContract->advisor_id,
            'collaborator_id' => $trainingContract->collaborator_id,
            'month' => $data['month'],
            'year' => $data['year'],
            'start' => Carbon::parse($data['start'])->toDateString(),
            'end' => Carbon::parse($data['end'])->toDateString(),
            'amount' => $data['amount'],
            'invoiced' => $data['invoiced'],
            'hours' => $data['hours']
        ]);

        return $bonus;
    }

    public static function bonusesWithNoBills($mainCompanyId) {
        $endOfMonth = Carbon::now()->endOfMonth();
        $bonuses = TrainingContractBonus::whereNotIn('id', function ($query) {
            $query->select('training_contract_bonus_id')
                ->from('training_contract_bills');
        })
            ->where('start', '<=', $endOfMonth->toDateString())
            ->where('main_company_id', $mainCompanyId)
            ->get();
        return $bonuses;
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('training_contract_bonuses.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(TrainingContractBonusService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TrainingContractBonusService::class);
        return $service->update($this, $data);
    }
}

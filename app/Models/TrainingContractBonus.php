<?php

namespace App\Models;

use App\Services\TrainingContractBonusService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function scopeGetBonuses($query, $trainingContractId, $mainCompanyId)
    {
        return $query
            ->select(
                'training_contract_bonuses.*',
                DB::raw("
                CASE month
                    WHEN 1 THEN 'Enero'
                    WHEN 2 THEN 'Febrero'
                    WHEN 3 THEN 'Marzo'
                    WHEN 4 THEN 'Abril'
                    WHEN 5 THEN 'Mayo'
                    WHEN 6 THEN 'Junio'
                    WHEN 7 THEN 'Julio'
                    WHEN 8 THEN 'Agosto'
                    WHEN 9 THEN 'Septiembre'
                    WHEN 10 THEN 'Octubre'
                    WHEN 11 THEN 'Noviembre'
                    WHEN 12 THEN 'Diciembre'
                END as month_name
            ")
            )
            ->where('training_contract_id', $trainingContractId)
            ->where('main_company_id', $mainCompanyId)
            ->orderBy('start', 'asc');
    }

    public function scopeGetBonus($query, $bonusId, $mainCompanyId)
    {
        return $query
            ->select(
                'training_contract_bonuses.*',
                DB::raw("
                CASE month
                    WHEN 1 THEN 'Enero'
                    WHEN 2 THEN 'Febrero'
                    WHEN 3 THEN 'Marzo'
                    WHEN 4 THEN 'Abril'
                    WHEN 5 THEN 'Mayo'
                    WHEN 6 THEN 'Junio'
                    WHEN 7 THEN 'Julio'
                    WHEN 8 THEN 'Agosto'
                    WHEN 9 THEN 'Septiembre'
                    WHEN 10 THEN 'Octubre'
                    WHEN 11 THEN 'Noviembre'
                    WHEN 12 THEN 'Diciembre'
                END as month_name
            ")
            )
            ->where('training_contract_bonuses.id', $bonusId)
            ->where('training_contract_bonuses.main_company_id', $mainCompanyId);
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

    public function scopeBonusesWithNoBills($query, $mainCompanyId)
    {
        $endOfMonth = Carbon::now()->endOfMonth();

        return $query
            ->whereNotIn('id', function ($sub) {
                $sub->select('training_contract_bonus_id')
                    ->from('training_contract_bills');
            })
            ->where('start', '<=', $endOfMonth->toDateString())
            ->where('main_company_id', $mainCompanyId);
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

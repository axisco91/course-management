<?php

namespace App\Http\Controllers\Api;
use App\Models\TrainingContract;
use App\Models\TrainingContractBonus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TrainingContractBonusController extends BaseController
{
    public function trainingContractBonuses($id) {
        try {
            return TrainingContractBonus::getBonuses($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

public function generate($id) {
    Log::info('generate method called with id', ['id' => $id]);
    $training_contract = TrainingContract::find($id);
    Log::info('Found training contract', ['training_contract' => $training_contract]);

    $training_contract_bonuses = TrainingContractBonus::where('training_contract_id', $training_contract->id)->get();
    Log::info('Found training contract bonuses', ['training_contract_bonuses' => $training_contract_bonuses]);

    if (count($training_contract_bonuses) === 0) {
        Log::info('No training contract bonuses found');

        $formation_hours = $training_contract->bonus_hours_first_year + $training_contract->bonus_hours_second_year;
        Log::info('Formation hours', ['formation_hours' => $formation_hours]);

        $beginning_date = Carbon::parse($training_contract->beginning_formation);
        log::info('Beginning date', ['beginning_date' => $beginning_date]);
        $end_date = Carbon::parse($training_contract->end_formation);
        log::info('End date', ['end_date' => $end_date]);

        $total_bonus = $formation_hours * 5;

        // Calcula el total de meses
        $total_months = $beginning_date->diffInMonths($end_date) + 1;

        // Calcula el amount para los meses completos, sin incluir el primer y último mes
        $full_month_amount = $total_bonus;
        $active_days_in_first_month = $beginning_date->diffInDays($beginning_date->copy()->endOfMonth()) + 1;
        $active_days_in_last_month = $end_date->day;

        // Calcula el número de días en el primer y último mes
        $days_in_first_month = $beginning_date->daysInMonth;
        $days_in_last_month = $end_date->daysInMonth;
        
        // Calcula el amount del primer y último mes
        $first_month_amount = ($active_days_in_first_month / $days_in_first_month) * ($total_bonus / $total_months);
        $last_month_amount = ($active_days_in_last_month / $days_in_last_month) * ($total_bonus / $total_months);
        $total_days = $beginning_date->diffInDays($end_date) + 1;
        $full_month_amount -= ($first_month_amount + $last_month_amount); // Resta los montos ajustados del total
        $amount_per_bonus = $full_month_amount / ($total_months - 2); // Divide el monto restante entre los meses restantes

        $period = CarbonPeriod::create($beginning_date, '1 month', $end_date->addMonth());
        foreach ($period as $date) {
            $active_days_in_month = $date->daysInMonth;
            $amount = ($active_days_in_month / $total_days) * $total_bonus;
        
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            // Redondea el amount cuando se crea el bono
            $amount = round($amount, 2);

            TrainingContractBonus::createBonus([
                'training_contract_id' => $id,
                'month' => $date->month,
                'year' => $date->year,
                'start' => $start,
                'end' => $end,
                'amount' => $amount,
                'hours' => 0,
                'invoiced' => 0
            ]);

            Log::info('Created training contract bonus', ['month' => $date->month, 'year' => $date->year]);
        }

        Log::info('Total amount of bonuses distributed');

        return response()->json([
            'status' => 200,
            'bonuses' => TrainingContractBonus::getBonuses($id)
        ]);
    } else {
        Log::info('Training contract bonuses already exist');
        return response()->json([
            'status' => 400,
            'message' => 'Bonuses for this training contract already exist'
        ]);
    }
}
    
    public function create(Request $request){
        try {
            $bonus = TrainingContractBonus::createBonus($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'bonus' => TrainingContractBonus::getBonus($bonus->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $bonus = TrainingContractBonus::updateBonus($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'bonus' => TrainingContractBonus::getBonus($bonus->id)
        ]);
    }

    public function getTrainingContractBonus($id){
        $bonus = TrainingContractBonus::getBonus($id);
        if ($bonus) {
            return response()->json([
                'status' => 200,
                'bonus' => $bonus
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Bonificado no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingContractBonus::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}
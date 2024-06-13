<?php

namespace App\Http\Controllers\Api;

use App\Models\TrainingContract;
use App\Models\TrainingContractBonus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
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
        $training_contract = TrainingContract::find($id);
        $training_contract_bonuses = TrainingContractBonus::where('training_contract_id', $training_contract->id)->get();

        if ($training_contract_bonuses->isEmpty()) {
            $beginning_date = Carbon::parse($training_contract->beginning);
            $end_date = Carbon::parse($training_contract->end);

            Log::info('Begin date: ' . $beginning_date);
            Log::info('End date: ' . $end_date);

            // Calcula el total de bonos para el primer y segundo año
            $total_bonus_first_year = $training_contract->bonus_hours_first_year * 5;
            $total_bonus_second_year = $training_contract->bonus_hours_second_year * 5;

            Log::info('Total bonus first year: ' . $total_bonus_first_year);
            Log::info('Total bonus second year: ' . $total_bonus_second_year);

            // Calcula el total amount
            $total_amount = $total_bonus_first_year + $total_bonus_second_year;

            Log::info('Total amount: ' . $total_amount);

            // Crear un periodo de un mes de duración desde la fecha de inicio hasta la fecha de finalización
            $period = CarbonPeriod::create($beginning_date, '1 month', $end_date->copy()->endOfMonth());

            $total_months = iterator_count($period);
            Log::info('Total months: ' . $total_months);

            // Calcular los días trabajados en el primer y último mes
            $days_in_first_month = $beginning_date->daysInMonth;
            $days_in_contract_first_month = $days_in_first_month - $beginning_date->day + 1;
            $days_in_last_month = $end_date->daysInMonth;
            $days_in_contract_last_month = $end_date->day;

            // Calcular el monto proporcional para el primer y último mes
            $amount_per_full_month = ($total_amount - (($total_amount / $total_months) * ($days_in_contract_first_month / $days_in_first_month) + ($total_amount / $total_months) * ($days_in_contract_last_month / $days_in_last_month))) / ($total_months - 2);
            $amount_first_month = ($total_amount / $total_months) * ($days_in_contract_first_month / $days_in_first_month);
            $amount_last_month = ($total_amount / $total_months) * ($days_in_contract_last_month / $days_in_last_month);

            // Redondear los montos al múltiplo de 5 más cercano
            $amount_first_month = round($amount_first_month / 5) * 5;
            $amount_last_month = round($amount_last_month / 5) * 5;
            $amount_per_full_month = round($amount_per_full_month / 5) * 5;

            Log::info('Amount per full month: ' . $amount_per_full_month);
            Log::info('Amount for first month: ' . $amount_first_month);
            Log::info('Amount for last month: ' . $amount_last_month);

            $amounts = [];
            foreach ($period as $key => $date) {
                if ($key == 0) {
                    $amount = $amount_first_month;
                } elseif ($key == $total_months - 1) {
                    $amount = $amount_last_month;
                } else {
                    $amount = $amount_per_full_month;
                }

                Log::info('Date: ' . $date . ' - Amount: ' . $amount);

                $amounts[] = $amount;
            }

            Log::info('Amounts before adjustment: ' . json_encode($amounts));

            // Ajustar cualquier diferencia residual en el último bono
            $difference = $total_amount - array_sum($amounts);
            if ($difference != 0) {
                $amounts[$total_months - 1] += $difference;
                $amounts[$total_months - 1] = round($amounts[$total_months - 1] / 5) * 5;
            }

            Log::info('Amounts after adjustment: ' . json_encode($amounts));

            // Crear los bonos con los montos ajustados
            foreach ($period as $key => $date) {
                $start_date = $date->copy()->startOfMonth();
                $end_date_for_bonus = $date->copy()->endOfMonth();

                if ($key == 0) {
                    $start_date = Carbon::parse($training_contract->beginning);
                }

                if ($key == $total_months - 1) {
                    $end_date_for_bonus = Carbon::parse($training_contract->end);
                }

                TrainingContractBonus::createBonus([
                    'training_contract_id' => $id,
                    'month' => $date->month,
                    'year' => $date->year,
                    'start' => $start_date,
                    'end' => $end_date_for_bonus,
                    'amount' => $amounts[$key],
                    'hours' => 0,
                    'invoiced' => 0
                ]);
            }

            $contract = TrainingContract::select('training_contracts.*')
                ->selectSub(function ($query) {
                    $query->from('training_contract_bonuses')
                        ->selectRaw('SUM(amount)')
                        ->whereColumn('training_contract_bonuses.training_contract_id', 'training_contracts.id');
                }, 'total_amount')
                ->leftJoin('training_contract_bonuses', 'training_contract_bonuses.training_contract_id', '=', 'training_contracts.id')
                ->where('training_contracts.id', $id)
                ->first();

            Log::info('Total amount from contract: ' . $contract->total_amount);

            return response()->json([
                'status' => 200,
                'bonuses' => TrainingContractBonus::getBonuses($id),
                'total_amount'=> $contract->total_amount,
            ]);

        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Bonos ya generados'
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
 
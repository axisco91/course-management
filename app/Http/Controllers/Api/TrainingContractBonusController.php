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
        $trainingContract = TrainingContract::find($id);
        $trainingContract_bonuses = TrainingContractBonus::where('training_contract_id', $trainingContract->id)->get();

        if ($trainingContract_bonuses->isEmpty()) {
            $beginning_date = Carbon::parse($trainingContract->beginning);
            $end_date = Carbon::parse($trainingContract->end);

            Log::info('Begin date: ' . $beginning_date);
            Log::info('End date: ' . $end_date);

            // Calcula el total de bonos para el primer y segundo año
            $total_bonus_first_year = $trainingContract->bonus_hours_first_year * 5;
            $total_bonus_second_year = $trainingContract->bonus_hours_second_year * 5;

            Log::info('Total bonus first year: ' . $total_bonus_first_year);
            Log::info('Total bonus second year: ' . $total_bonus_second_year);

            // Calcula el total amount
            $total_amount = $total_bonus_first_year + $total_bonus_second_year;
            Log::info('Total amount: ' . $total_amount);

            // Crear un periodo de un mes de duración desde la fecha de inicio hasta la fecha de finalización
            $period = CarbonPeriod::create($beginning_date, '1 month', $end_date->copy()->endOfMonth());

            $total_months = iterator_count($period);
            Log::info('Total months: ' . $total_months);

            $amounts = [];

            foreach ($period as $key => $date) {
                // Determine if the month falls in the first or second year
                $is_first_year = $date->year == $beginning_date->year || ($date->year == $beginning_date->year + 1 && $date->month <= $beginning_date->month);

                if ($is_first_year) {
                    $amount_per_full_month = $total_bonus_first_year / 12;
                } else {
                    $amount_per_full_month = $total_bonus_second_year / 12;
                }

                // Calcular los días trabajados en el primer y último mes
                if ($key == 0) {
                    $days_in_first_month = $date->daysInMonth;
                    $days_in_contract_first_month = $days_in_first_month - $beginning_date->day + 1;
                    $amount = $amount_per_full_month * ($days_in_contract_first_month / $days_in_first_month);
                } elseif ($key == $total_months - 1) {
                    $days_in_last_month = $end_date->daysInMonth;
                    $days_in_contract_last_month = $end_date->day;
                    $amount = $amount_per_full_month * ($days_in_contract_last_month / $days_in_last_month);
                } else {
                    // Manejo del mes puente
                    if ($date->year == $beginning_date->year + 1 && $date->month == $beginning_date->month) {
                        $days_in_month = $date->daysInMonth;
                        $days_in_first_year = $beginning_date->copy()->addYear()->startOfMonth()->diffInDays($date->copy()->endOfMonth()->startOfMonth());
                        $days_in_second_year = $days_in_month - $days_in_first_year;
                        Log::info('Bridge month: ' . $date . ' - Days in first year: ' . $days_in_first_year . ' - Days in second year: ' . $days_in_second_year);
                        $amount = (($total_bonus_first_year / 12) * ($days_in_first_year / $days_in_month)) + (($total_bonus_second_year / 12) * ($days_in_second_year / $days_in_month));
                    } else {
                        $amount = $amount_per_full_month;
                    }
                }

                // Redondear los montos al múltiplo de 5 más cercano
                $amount = round($amount / 5) * 5;

                Log::info('Date: ' . $date . ' - Amount: ' . $amount);

                $amounts[] = $amount;
            }

            Log::info('Amounts before adjustment: ' . json_encode($amounts));

            // Ajustar cualquier diferencia residual
            $difference = $total_amount - array_sum($amounts);
            Log::info('Initial difference: ' . $difference);

            // Distribuir la diferencia en los meses, asegurando que ningún monto sea demasiado grande o pequeño
            if (abs($difference) >= 5) {
                $increment = $difference > 0 ? 5 : -5;
                for ($i = 0; $i < count($amounts) && abs($difference) >= 5; $i++) {
                    $amounts[$i] += $increment;
                    $difference -= $increment;
                }
            }

            Log::info('Adjusted amounts: ' . json_encode($amounts));
            Log::info('Remaining difference: ' . $difference);

            // Asegurar que ningún monto sea menor que 5 o desproporcionadamente grande
            while ($difference != 0) {
                for ($i = 0; $i < count($amounts); $i++) {
                    if ($difference == 0) break;

                    if ($difference > 0 && $amounts[$i] >= 5) {
                        $amounts[$i] += 5;
                        $difference -= 5;
                    } elseif ($difference < 0 && $amounts[$i] > 5) {
                        $amounts[$i] -= 5;
                        $difference += 5;
                    }
                }
            }

            Log::info('Amounts after final adjustment: ' . json_encode($amounts));

            // Crear los bonos con los montos ajustados
            foreach ($period as $key => $date) {
                $start_date = $date->copy()->startOfMonth();
                $end_date_for_bonus = $date->copy()->endOfMonth();

                if ($key == 0) {
                    $start_date = Carbon::parse($trainingContract->beginning);
                }

                if ($key == $total_months - 1) {
                    $end_date_for_bonus = Carbon::parse($trainingContract->end);
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

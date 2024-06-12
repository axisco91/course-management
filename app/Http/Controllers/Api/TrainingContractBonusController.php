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

            // Calcula el número de meses en el primer y segundo año del contrato
            $months_in_first_year = min(12, $beginning_date->diffInMonths($end_date) + 1);
            $months_in_second_year = max(0, $beginning_date->diffInMonths($end_date) + 1 - 12);

            Log::info('Months in first year: ' . $months_in_first_year);
            Log::info('Months in second year: ' . $months_in_second_year);

            // Calcula el amount por mes para el primer y segundo año
            $amount_per_month_first_year = $months_in_first_year > 0 ? $total_bonus_first_year / $months_in_first_year : 0;
            $amount_per_month_second_year = $months_in_second_year > 0 ? $total_bonus_second_year / $months_in_second_year : 0;

            Log::info('Amount per month first year: ' . $amount_per_month_first_year);
            Log::info('Amount per month second year: ' . $amount_per_month_second_year);

            $period = CarbonPeriod::create($beginning_date, '1 month', $end_date);

            $amounts = [];
            foreach ($period as $key => $date) {
                // Usa el amount por mes del primer año para los meses en el primer año, y el amount por mes del segundo año para los meses en el segundo año
                if ($date->lt($beginning_date->copy()->addYear())) {
                    $amount = $amount_per_month_first_year;
                } else {
                    $amount = $amount_per_month_second_year;
                }

                Log::info('Date: ' . $date . ' - Initial amount: ' . $amount);

                // Si es el primer mes del contrato, ajusta el amount para tener en cuenta que el mes puede no ser completo
                if ($date->equalTo($beginning_date)) {
                    $days_in_month = $date->daysInMonth;
                    $days_in_contract = $date->copy()->endOfMonth()->diffInDays(Carbon::parse($training_contract->beginning)) + 1;
                    $amount *= $days_in_contract / $days_in_month;
                    Log::info('Adjusted first month amount: ' . $amount);
                }

                // Si es el último mes del contrato, ajusta el amount para tener en cuenta que el mes puede no ser completo
                if ($date->equalTo($end_date)) {
                    $days_in_month = $date->daysInMonth;
                    $days_in_contract = Carbon::parse($training_contract->end)->diffInDays($date->copy()->startOfMonth()) + 1;
                    $amount *= $days_in_contract / $days_in_month;
                    Log::info('Adjusted last month amount: ' . $amount);
                }

                // Redondea el amount al múltiplo de 5 más cercano
                $amount = round($amount / 5) * 5;

                Log::info('Final amount for ' . $date . ': ' . $amount);

                $amounts[] = $amount;
            }

            Log::info('Amounts before adjustment: ' . json_encode($amounts));

            // Calcula la diferencia entre total_amount y la suma de los amounts
            $difference = $total_amount - array_sum($amounts);

            Log::info('Difference before adjustment: ' . $difference);

            // Ajusta la diferencia para que sea un múltiplo de 5
            $difference = round($difference / 5) * 5;

            Log::info('Rounded difference: ' . $difference);

            // Distribuir la diferencia de manera uniforme entre todos los meses del segundo año
            if ($difference != 0) {
                $second_year_start_index = null;
                foreach ($period as $key => $date) {
                    if ($date->gte($beginning_date->copy()->addYear())) {
                        $second_year_start_index = $key;
                        break;
                    }
                }

                if ($second_year_start_index !== null) {
                    $months_in_second_year = count($amounts) - $second_year_start_index;
                    $amount_per_month_adjustment = $difference / $months_in_second_year;

                    for ($i = $second_year_start_index; $i < count($amounts) - 1; $i++) {
                        $amounts[$i] += $amount_per_month_adjustment;
                        // Redondea el amount al múltiplo de 5 más cercano
                        $amounts[$i] = round($amounts[$i] / 5) * 5;
                    }

                    // Recalcular la diferencia final después del ajuste
                    $final_difference = $total_amount - array_sum($amounts);

                    // Ajustar los meses del segundo año uniformemente sin que el último sea mayor
                    $amount_per_month_adjustment = $final_difference / $months_in_second_year;
                    for ($i = $second_year_start_index; $i < count($amounts) - 1; $i++) {
                        $amounts[$i] += $amount_per_month_adjustment;
                        // Redondea el amount al múltiplo de 5 más cercano
                        $amounts[$i] = round($amounts[$i] / 5) * 5;
                    }

                    // Ajustar el último mes si queda una diferencia residual
                    $final_difference = $total_amount - array_sum($amounts);
                    if ($final_difference != 0) {
                        $amounts[count($amounts) - 1] += $final_difference;
                        // Redondea el amount al múltiplo de 5 más cercano
                        $amounts[count($amounts) - 1] = round($amounts[count($amounts) - 1] / 5) * 5;
                    }
                }
            }

            Log::info('Amounts after adjustment: ' . json_encode($amounts));

            // Crea los bonos con los amounts ajustados
            foreach ($period as $key => $date) {
                $start_date = $date->copy()->startOfMonth();
                if ($date->equalTo($beginning_date)) {
                    $start_date = Carbon::parse($training_contract->beginning);
                }

                $end_date = $date->copy()->endOfMonth();
                if ($date->equalTo($end_date)) {
                    $end_date = Carbon::parse($training_contract->end);
                }

                TrainingContractBonus::createBonus([
                    'training_contract_id' => $id,
                    'month' => $date->month,
                    'year' => $date->year,
                    'start' => $start_date,
                    'end' => $end_date,
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

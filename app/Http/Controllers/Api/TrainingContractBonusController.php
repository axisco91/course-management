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
        $training_contract = TrainingContract::find($id);
        $training_contract_bonuses = TrainingContractBonus::where('training_contract_id', $training_contract->id)->get();

        if (count($training_contract_bonuses) === 0) {
            $beginning_date = Carbon::parse($training_contract->beginning);
            $end_date = Carbon::parse($training_contract->end);

            // Calcula el total de bonos para el primer y segundo año
            $total_bonus_first_year = $training_contract->bonus_hours_first_year * 5;
            $total_bonus_second_year = $training_contract->bonus_hours_second_year * 5;

            // Calcula el total amount
            $total_amount = $total_bonus_first_year + $total_bonus_second_year;

            // Calcula el número de meses en el primer y segundo año del contrato
            $months_in_first_year = min(12, $beginning_date->diffInMonths($end_date) + 1);
            $months_in_second_year = max(0, $beginning_date->diffInMonths($end_date) + 1 - 12);

            // Calcula el amount por mes para el primer y segundo año
            $amount_per_month_first_year = $total_bonus_first_year / $months_in_first_year;
            $amount_per_month_second_year = $months_in_second_year > 0 ? $total_bonus_second_year / $months_in_second_year : 0;

            $period = CarbonPeriod::create($beginning_date, '1 month', $end_date->addMonth());

            $amounts = [];
            foreach ($period as $key => $date) {
                // Usa el amount por mes del primer año para los meses en el primer año, y el amount por mes del segundo año para los meses en el segundo año
                if ($date->lt($beginning_date->copy()->addYear())) {
                    $amount = $amount_per_month_first_year;
                } else {
                    $amount = $amount_per_month_second_year;
                }

                // Si es el primer mes del contrato, ajusta el amount para tener en cuenta que el mes puede no ser completo
                if ($key == 0) {
                    $days_in_month = $date->daysInMonth;
                    $days_in_contract = $date->copy()->endOfMonth()->diffInDays(Carbon::parse($training_contract->beginning)) + 1;
                    $amount *= $days_in_contract / $days_in_month;
                }

                // Si es el último mes del contrato, ajusta el amount para tener en cuenta que el mes puede no ser completo
                if ($key == count($period) - 1) {
                    $days_in_month = $date->daysInMonth;
                    $days_in_contract = Carbon::parse($training_contract->end)->diffInDays($date->copy()->startOfMonth()) + 1;
                    $amount *= $days_in_contract / $days_in_month;
                }

                // Redondea el amount al múltiplo de 5 más cercano
                $amount = round($amount / 5) * 5;

                $amounts[] = $amount;
            }

            // Calcula la diferencia entre total_amount y la suma de los amounts
            $difference = $total_amount - array_sum($amounts);

            // Ajusta la diferencia para que sea un múltiplo de 5
            $difference = round($difference / 5) * 5;

            // Ajusta el último amount para compensar la diferencia
            $amounts[count($amounts) - 1] += $difference;

            // Crea los bonos con los amounts ajustados
            foreach ($period as $key => $date) {
                $start_date = $date->copy()->startOfMonth();
                if ($key == 0) {
                    $start_date = Carbon::parse($training_contract->beginning);
                }

                $end_date = $date->copy()->endOfMonth();
                if ($key == count($period) - 1) {
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
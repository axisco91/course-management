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

        $beginning_date = Carbon::parse($training_contract->beginning_formation);
        log::info('Beginning date', ['beginning_date' => $beginning_date]);
        $end_date = Carbon::parse($training_contract->end_formation);
        log::info('End date', ['end_date' => $end_date]);

        // Calcula el total de bonos para el primer y segundo año
        $total_bonus_first_year = $training_contract->bonus_hours_first_year * 5;
        $total_bonus_second_year = $training_contract->bonus_hours_second_year * 5;

        // Calcula el amount por mes para el primer y segundo año
        $amount_per_month_first_year = $total_bonus_first_year / 12; // Prorratea para 12 meses
        $amount_per_month_second_year = $total_bonus_second_year / 12; // Prorratea para 12 meses

        $period = CarbonPeriod::create($beginning_date, '1 month', $end_date);

        $amounts = [];
        foreach ($period as $key => $date) {
            // Usa el amount por mes del primer año para los meses en el primer año, y el amount por mes del segundo año para los meses en el segundo año
            if ($date->lt($beginning_date->copy()->addYear())) {
                $amount = $amount_per_month_first_year;
            } else {
                $amount = $amount_per_month_second_year;
            }

          // Si es el último mes del primer año, ajusta el amount para que la suma total sea exactamente igual al total_bonus del primer año
        if ($date->month == $beginning_date->copy()->addYear()->month && $date->year == $beginning_date->copy()->addYear()->year) {
            $amount = $total_bonus_first_year - array_sum($amounts);
            $amounts = []; // Resetea los amounts para el segundo año
        }

        // Si es el último mes del segundo año, ajusta el amount para que la suma total sea exactamente igual al total_bonus del segundo año
        if ($key == count($period) - 1) {
            $amount = $total_bonus_second_year - array_sum($amounts);
        }

        if ($amount < 0) {
            $amount = 0;
        }

        // Redondea el amount después de ajustarlo
        $amount = round($amount);

        $amounts[] = $amount;;

            TrainingContractBonus::createBonus([
                'training_contract_id' => $id,
                'month' => $date->month,
                'year' => $date->year,
                'start' => $date->copy()->startOfMonth(),
                'end' => $date->copy()->endOfMonth(),
                'amount' => $amount,
                'hours' => 0,
                'invoiced' => 0
            ]);

            Log::info('Created training contract bonus', ['month' => $date->month, 'year' => $date->year, 'amount' => $amount ]);
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
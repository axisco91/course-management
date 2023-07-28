<?php

namespace App\Http\Controllers\Api;
use App\Models\TrainingContract;
use App\Models\TrainingContractBonus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            $formation_hours = $training_contract->formation_hours;
            $total_months = 0;
            $beginning_date = Carbon::parse($training_contract->beginning_formation);
            $end_date = Carbon::parse($training_contract->end_formation);
            $first_month = $beginning_date->format('m');
            $first_year = $beginning_date->format('Y');
            $last_month = $end_date->format('m');
            $last_year = $end_date->format('Y');
            $cont = $first_year;
            $i = $first_month;
            for($cont;$cont <= $last_year; $cont++) {
                if ($cont == $last_year) {
                    $k = $last_month;
                } else {
                    $k = 12;
                }
               for ($i; $i <= $k; $i++) {
                   $total_months++;
               }
               $i = 1;
            }
            $fixed_month = $formation_hours/$total_months;
            $fixed_month = (int) $fixed_month;
            $rest_month = $formation_hours - ($fixed_month * $total_months);
            $actual_date = Carbon::now();
            $actual_last_month = $last_month;
            if ($actual_date->format('m') <= $last_month) {
                $actual_last_month = $actual_date->format('m');
            }
            if ($actual_date->format('Y') <= $last_year) {
                $last_year = $actual_date->format('Y');
            }
            $cont = $first_year;
            $i = $first_month;
            for($cont;$cont <= $last_year; $cont++) {
                if ($cont == $last_year) {
                    $k = $actual_date->format('m');
                } else {
                    $k = 12;
                }
                for ($i; $i <= $k; $i++) {
                    if ($cont == $first_year && $i == $first_month) {
                        $amount = ($fixed_month + $rest_month) * 5;
                        $start = $beginning_date;
                        $end = $beginning_date;
                    } else {
                        $amount = $fixed_month * 5;
                        $start = Carbon::createFromFormat('d/m/Y', '01/'.$i.'/'.$cont);
                        $end = Carbon::createFromFormat('d/m/Y', '01/'.$i.'/'.$cont);
                    }
                    if ($cont == $last_year && $i == $last_month) {
                        $end = $end_date;
                    } else {
                        $start = $start->toDateString();
                        $end = $end->endOfMonth()->toDateString();
                    }
                    TrainingContractBonus::createBonus([
                        'training_contract_id' => $id,
                        'month' => $i,
                        'year' => $cont,
                        'start' => $start,
                        'end' => $end,
                        'amount' => $amount,
                        'hours' => 0,
                        'invoiced' => 0
                    ]);
                }
                $i = 1;
            }
            return response()->json([
                'status' => 200,
                'bonuses' => TrainingContractBonus::getBonuses($id)
            ]);
        } else {
            $formation_hours = $training_contract->formation_hours;
            $total_months = 0;
            $beginning_date = Carbon::parse($training_contract->beginning_formation);
            $end_date = Carbon::parse($training_contract->end_formation);
            $first_month = $beginning_date->format('m');
            $first_year = $beginning_date->format('Y');
            $last_month = $end_date->format('m');
            $last_year = $end_date->format('Y');
            $cont = $first_year;
            $i = $first_month;
            for($cont;$cont <= $last_year; $cont++) {
                if ($cont == $last_year) {
                    $k = $last_month;
                } else {
                    $k = 12;
                }
                for ($i; $i <= $k; $i++) {
                    $total_months++;
                }
                $i = 1;
            }
            $fixed_month = $formation_hours/$total_months;
            $fixed_month = (int) $fixed_month;
            $rest_month = $formation_hours - ($fixed_month * $total_months);
            $actual_date = Carbon::now();
            $actual_last_month = $last_month;
            if ($actual_date->format('m') <= $last_month) {
                $actual_last_month = $actual_date->format('m');
            }
            if ($actual_date->format('Y') <= $last_year) {
                $last_year = $actual_date->format('Y');
            }
            $cont = $first_year;
            $i = $first_month;
            for($cont;$cont <= $last_year; $cont++) {
                if ($cont == $last_year) {
                    $k = $actual_last_month;
                } else {
                    $k = 12;
                }
                for ($i; $i <= $k; $i++) {
                    if ($cont == $first_year && $i == $first_month) {
                        $amount = ($fixed_month + $rest_month) * 5;
                        $start = $beginning_date;
                        $end = $beginning_date;
                    } else {
                        $amount = $fixed_month * 5;
                        $start = Carbon::createFromFormat('d/m/Y', '01/'.$i.'/'.$cont);
                        $end = Carbon::createFromFormat('d/m/Y', '01/'.$i.'/'.$cont);
                    }
                    if ($cont == $last_year && $i == $last_month) {
                        $end = $end_date;
                    } else {
                        $start = $start->toDateString();
                        $end = $end->endOfMonth()->toDateString();
                    }
                    $training_contract_bonus = TrainingContractBonus::where('training_contract_id', $id)
                        ->where('month', $i)
                        ->where('year', $cont)
                        ->first();
                    if (!$training_contract_bonuses) {
                        TrainingContractBonus::createBonus([
                            'training_contract_id' => $id,
                            'month' => $i,
                            'year' => $cont,
                            'start' => $start,
                            'end' => $end,
                            'amount' => $amount,
                            'hours' => 0,
                            'invoiced' => 0
                        ]);
                    }
                }
                $i = 1;
            }
            return response()->json([
                'status' => 200,
                'bonuses' => TrainingContractBonus::getBonuses($id)
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

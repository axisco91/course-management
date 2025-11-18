<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Models\TrainingContract;
use App\Models\TrainingContractBonus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TrainingContractBonusController extends BaseController
{
    public function trainingContractBonuses($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            return TrainingContractBonus::getBonuses($id, $mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function generate($id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $trainingContract = TrainingContract::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->firstOrFail();

        $beginning_date = Carbon::parse($trainingContract->beginning);
        $end_date       = Carbon::parse($trainingContract->end);

        // 1) Trae existentes
        $existing = TrainingContractBonus::where('training_contract_id', $trainingContract->id)
            ->FilterMainCompany($mainCompanyId)
            ->get(['year','month'])
            ->map(fn($b) => sprintf('%04d-%02d', $b->year, $b->month))
            ->toArray();

        // 2) Construye todos los meses del rango
        $period  = CarbonPeriod::create($beginning_date, '1 month', $end_date->copy()->endOfMonth());
        $months  = iterator_to_array($period); // ¡no reutilizamos el iterador!
        $total_months = count($months);

        // 3) Calcula importes y crea SOLO los faltantes
        $total_bonus_first_year  = $trainingContract->bonus_hours_first_year  * 5;
        $total_bonus_second_year = $trainingContract->bonus_hours_second_year * 5;

        foreach ($months as $idx => $date) {
            $ymKey = sprintf('%04d-%02d', $date->year, $date->month);
            if (in_array($ymKey, $existing, true)) {
                continue; // este mes ya estaba
            }

            // ¿primer/segundo año?
            $is_first_year = ($date->year == $beginning_date->year)
                || ($date->year == $beginning_date->year + 1 && $date->month <= $beginning_date->month);

            $amount_per_full_month = ($is_first_year ? $total_bonus_first_year : $total_bonus_second_year) / 12;

            // prorrateos inicio/fin/puente (igual que tu lógica)
            if ($idx === 0) {
                $days_in_first_month = $date->daysInMonth;
                $days_in_contract_first_month = $days_in_first_month - $beginning_date->day + 1;
                $amount = $amount_per_full_month * ($days_in_contract_first_month / $days_in_first_month);
            } elseif ($idx === $total_months - 1) {
                $days_in_last_month = $end_date->daysInMonth;
                $days_in_contract_last_month = $end_date->day;
                $amount = $amount_per_full_month * ($days_in_contract_last_month / $days_in_last_month);
            } else {
                if ($date->year == $beginning_date->year + 1 && $date->month == $beginning_date->month) {
                    $days_in_month = $date->daysInMonth;
                    // divide el mes “puente” entre año 1 y 2
                    $boundary = $beginning_date->copy()->addYear()->startOfMonth();
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth   = $date->copy()->endOfMonth();

                    $days_in_first_year  = max(0, min($days_in_month, $boundary->diffInDays($endOfMonth) - $boundary->diffInDays($startOfMonth)));
                    $days_in_second_year = $days_in_month - $days_in_first_year;

                    $amount = (($total_bonus_first_year  / 12) * ($days_in_first_year  / $days_in_month))
                        + (($total_bonus_second_year / 12) * ($days_in_second_year / $days_in_month));
                } else {
                    $amount = $amount_per_full_month;
                }
            }

            $amount = round($amount / 5) * 5;

            $start_date = $idx === 0 ? Carbon::parse($trainingContract->beginning) : $date->copy()->startOfMonth();
            $end_date_for_bonus = $idx === $total_months - 1 ? Carbon::parse($trainingContract->end) : $date->copy()->endOfMonth();

            // crea solo si no existe
            TrainingContractBonus::firstOrCreate(
                [
                    'training_contract_id' => $id,
                    'year'  => $date->year,
                    'month' => $date->month,
                    'main_company_id' => $trainingContract->main_company_id,
                ],
                [
                    'start'   => $start_date,
                    'end'     => $end_date_for_bonus,
                    'amount'  => $amount,
                    'hours'   => 0,
                    'invoiced'=> 0,
                ]
            );
        }

        // Total recalculado
        $contract = TrainingContract::select('training_contracts.*')
            ->selectSub(function ($q) {
                $q->from('training_contract_bonuses')
                    ->selectRaw('SUM(amount)')
                    ->whereColumn('training_contract_bonuses.training_contract_id', 'training_contracts.id');
            }, 'total_amount')
            ->leftJoin('training_contract_bonuses', 'training_contract_bonuses.training_contract_id', '=', 'training_contracts.id')
            ->where('training_contracts.id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        return response()->json([
            'status'       => 200,
            'message'      => 'Bonos generados para los meses faltantes',
            'bonuses'      => TrainingContractBonus::getBonuses($id, $mainCompanyId),
            'total_amount' => $contract->total_amount,
        ]);
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;
            $bonus = TrainingContractBonus::createWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'bonus' => TrainingContractBonus::getBonus($bonus->id, $mainCompanyId),
        ]);
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $bonus = TrainingContractBonus::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$bonus){
                return response()->json([
                    'status' => 404,
                    'message' => 'Bonificado no existe'
                ]);
            }

            $data = $request->all();
            $bonus->updateWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'bonus' => TrainingContractBonus::getBonus($bonus->id, $mainCompanyId),
        ]);
    }

    public function getTrainingContractBonus($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $bonus = TrainingContractBonus::getBonus($id, $mainCompanyId);
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

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $bonus = TrainingContractBonus::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$bonus){
                    return response()->json([
                        'status' => 404,
                        'message' => 'Bonificado no existe'
                    ]);
                }

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

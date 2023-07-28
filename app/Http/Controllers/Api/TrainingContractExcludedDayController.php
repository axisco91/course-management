<?php

namespace App\Http\Controllers\Api;
use App\Models\ExcludedDayType;
use App\Models\TrainingContract;
use App\Models\TrainingContractsExcludedDay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingContractExcludedDayController extends BaseController
{

    public function getTrainingContractExcludedDays(Request $request) {
        if ($request->has('id')) {
            try {
                return TrainingContractsExcludedDay::sameGroup($request->id)
                    ->get();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function create(Request $request){
        if ($request['beginning'] && $request['end'] && $request['excluded_day_type_id']) {
            TrainingContractsExcludedDay::createExcludedDay($request);
            return response()->json([
                'status' => 200,
                'training_contract_excluded_days' =>  TrainingContractsExcludedDay::select(DB::raw("CONCAT(excluded_day_types.name, ' ', DATE_FORMAT(MIN(training_contracts_excluded_days.day), '%e/%c/%Y'), ' - ', DATE_FORMAT(MAX(training_contracts_excluded_days.day), '%e/%c/%Y'), ' Número de dias: ', COUNT(*)) as name"))
                    ->join('excluded_day_types', 'excluded_day_types.id', '=', 'training_contracts_excluded_days.excluded_day_type_id')
                    ->where('training_contract_id', $request->training_contract_id)
                    ->groupBy('group')
                    ->get()
            ]);
        }
        return response()->json([
            'status' => 400,
            'error' => 'No has introducido uan fecha'
        ]);
    }

    public function createGroup(Request $request){

        $training_contract = TrainingContract::find($request['training_contract_id']);
        if ($request['type'] === 'general') {
            TrainingContractsExcludedDay::addGeneralDays($request['training_contract_id'], $training_contract->beginning, $training_contract->end);
        } else {
            TrainingContractsExcludedDay::createTrainingContractExcludedDay($request['training_contract_id'], $request['id'], $request['type'], $training_contract->beginning, $training_contract->end);
        }
        return response()->json([
            'status' => 200,
            'training_contract_excluded_days' => TrainingContractsExcludedDay::select('training_contracts_excluded_days.*', 'excluded_days.day')
                ->leftJoin('excluded_days', 'excluded_days.id', '=', 'training_contracts_excluded_days.excluded_day_id')
                ->where('training_contract_id', $request['training_contract_id'])->get()
        ]);
    }

    public function destroy($group){
        if ($group) {
            try {
                $excludedDays = TrainingContractsExcludedDay::where('group', $group)->get();
                foreach ($excludedDays as $excludedDay) {
                    TrainingContractsExcludedDay::destroy($excludedDay->id);
                }
                return response()->json([
                    'status' => 200,
                    'excluded' => $excludedDays
                ]);
                return response()->json([
                    'status' => 200,
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

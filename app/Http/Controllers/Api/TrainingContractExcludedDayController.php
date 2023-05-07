<?php

namespace App\Http\Controllers\API;
use App\Models\ExcludedDay;
use App\Models\TrainingContract;
use App\Models\TrainingContractsExcludedDay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TrainingContractExcludedDayController extends BaseController
{

    public function getTrainingContractExcludedDays(Request $request) {
        if ($request->has('id')) {
            try {
                return TrainingContractsExcludedDay::select('training_contracts_excluded_days.*', 'excluded_days.day')
                    ->leftJoin('excluded_days', 'excluded_days.id', '=', 'training_contracts_excluded_days.excluded_day_id')
                    ->where('training_contract_id', $request->id)->get();
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function create(Request $request){
        if ($request['date']) {
            $excluded_day = ExcludedDay::where('day', $request['date'])->first();
            if (!$excluded_day){
                $excluded_day = ExcludedDay::create([
                    'day' => Carbon::createFromFormat('d-m-Y', $request['date'])->format('Y-m-d'),
                    'general' => 0
                ]);
            }
            $training_contract_excluded_day = TrainingContractsExcludedDay::create([
                'training_contract_id' => $request['training_contract_id'],
                'excluded_day_id' => $excluded_day->id
            ]);
            return response()->json([
                'status' => 200,
                'training_contract_excluded_day' =>  TrainingContractsExcludedDay::select('training_contracts_excluded_days.*', 'excluded_days.day')
                    ->leftJoin('excluded_days', 'excluded_days.id', '=', 'training_contracts_excluded_days.excluded_day_id')
                    ->where('training_contracts_excluded_days.id', $training_contract_excluded_day->id)->first()
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

    public function destroy($id){
        if ($id) {
            try {
                TrainingContractsExcludedDay::destroy($id);
                return response()->json([
                    'status' => 200,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

}

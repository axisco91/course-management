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
        $data = json_decode($request->getContent(), true);
        if ($data['date']) {
            $excluded_day = ExcludedDay::where('day', $data['date'])->first();
            if (!$excluded_day){
                $excluded_day = ExcludedDay::create([
                    'day' => Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'),
                    'general' => 0
                ]);
            }
            $training_contract_excluded_day = TrainingContractsExcludedDay::create([
                'training_contract_id' => $data['training_contract_id'],
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
        $data = json_decode($request->getContent(), true);
        $training_contract = TrainingContract::find($data['training_contract_id']);
        if ($data['type'] === 'general') {
            TrainingContractsExcludedDay::addGeneralDays($data['training_contract_id'], $training_contract->beginning, $training_contract->end);
        } else {
            TrainingContractsExcludedDay::createTrainingContractExcludedDay($data['training_contract_id'], $data['id'], $data['type'], $training_contract->beginning, $training_contract->end);
        }
        return response()->json([
            'status' => 200,
            'training_contract_excluded_days' => TrainingContractsExcludedDay::select('training_contracts_excluded_days.*', 'excluded_days.day')
                ->leftJoin('excluded_days', 'excluded_days.id', '=', 'training_contracts_excluded_days.excluded_day_id')
                ->where('training_contract_id', $data['training_contract_id'])->get()
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

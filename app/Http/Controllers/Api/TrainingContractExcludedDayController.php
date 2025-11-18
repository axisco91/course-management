<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\TrainingContract;
use App\Models\TrainingContractsExcludedDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingContractExcludedDayController extends BaseController
{

    public function getTrainingContractExcludedDays(Request $request) {
        if ($request->has('id')) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                return TrainingContractsExcludedDay::sameGroup($request->id, $mainCompanyId)
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;
            TrainingContractsExcludedDay::createExcludedDay($data);
            return response()->json([
                'status' => 200,
                'training_contract_excluded_days' =>  TrainingContractsExcludedDay::sameGroup($request->training_contract_id, $mainCompanyId)
                    ->get()
            ]);
        }
        return response()->json([
            'status' => 400,
            'error' => 'No has introducido uan fecha'
        ]);
    }

    public function createGroup(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $trainingContract = TrainingContract::find($request['training_contract_id']);
        if ($request['type'] === 'general') {
            TrainingContractsExcludedDay::addGeneralDays($request['training_contract_id'], $trainingContract->beginning, $trainingContract->end);
        } else {
            TrainingContractsExcludedDay::createTrainingContractExcludedDay($request['training_contract_id'], $request['id'], $request['type'], $trainingContract->beginning, $trainingContract->end, $mainCompanyId);
        }
        return response()->json([
            'status' => 200,
            'training_contract_excluded_days' => TrainingContractsExcludedDay::select('training_contracts_excluded_days.*', 'excluded_days.day')
                ->leftJoin('excluded_days', 'excluded_days.id', '=', 'training_contracts_excluded_days.excluded_day_id')
                ->where('training_contract_id', $request['training_contract_id'])->get()
        ]);
    }

    public function destroy($group, Request $request){
        if ($group) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $excludedDays = TrainingContractsExcludedDay::where('group', $group)
                    ->FilterMainCompany($mainCompanyId)
                    ->get();
                foreach ($excludedDays as $excludedDay) {
                    TrainingContractsExcludedDay::destroy($excludedDay->id);
                }
                return response()->json([
                    'status' => 200,
                    'excluded' => $excludedDays
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

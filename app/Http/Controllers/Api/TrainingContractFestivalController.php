<?php

namespace App\Http\Controllers\Api;
use App\Models\TrainingContract;
use App\Models\TrainingContractFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TrainingContractFestivalController extends BaseController
{

    public function getTrainingContractFestivals(Request $request) {
        if ($request->has('id')) {
            try {
                return TrainingContractFestival::festivals()->where('training_contract_id', $request->id)->orderBy('day')->get();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function create(Request $request){
        try {
            $training_contract = TrainingContract::where('id', $request['training_contract_id'])->first();
            TrainingContractFestival::createTrainingContractFestivals($request['training_contract_id'], $request['id'], $request['type'], $training_contract->beginning_formation, $training_contract->end_formation);
            return response()->json([
                'status' => 200,
                'training_contract_festivals' =>  TrainingContractFestival::festivals()->where('training_contract_id', $training_contract->id)->get()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingContractFestival::destroy($id);
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

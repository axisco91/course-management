<?php

namespace App\Http\Controllers\Api;
use App\Models\TrainingContractStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractStatusController extends BaseController
{
    public function getTrainingContractStatuses() {
        try {
            return TrainingContractStatus::getTrainingContractStatuses();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $status = TrainingContractStatus::createTrainingContractStatus($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract_status' => TrainingContractStatus::getTrainingContractStatus($status->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $status = TrainingContractStatus::updateTrainingContractStatus($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract_status' => TrainingContractStatus::getTrainingContractStatus($status->id)
        ]);
    }

    public function getTrainingContractStatus($id){
        $status = TrainingContractStatus::getTrainingContractStatus($id);
        if ($status) {
            return response()->json([
                'status' => 200,
                'training_contract_status' => $status
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Estado no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingContractStatus::destroy($id);
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

    public function count() {
        return TrainingContractStatus::count();
    }
}

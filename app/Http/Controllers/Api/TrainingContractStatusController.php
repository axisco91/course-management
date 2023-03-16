<?php

namespace App\Http\Controllers\API;
use App\Models\TrainingContractStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractStatusController extends BaseController
{
    public function getTrainingContractStatuses() {
        try {
            return TrainingContractStatus::getTrainingContractStatus();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $status = TrainingContractStatus::createTrainingContractStatus($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract_status' => $status
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $status = TrainingContractStatus::updateTrainingContractStatus($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract_status' => $status
        ]);
    }

    public function getTrainingContractStatus($id){
        $status = TrainingContractStatus::find($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function count() {
        return TrainingContractStatus::count();
    }
}

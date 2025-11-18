<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingUnitController extends BaseController
{
    public function trainingUnits(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            return TrainingUnit::getTrainingUnits($mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $training_unit = TrainingUnit::creatWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_unit' => TrainingUnit::getTrainingUnit($training_unit->id, $mainCompanyId)
        ]);
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $trainingUnit = TrainingUnit::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$trainingUnit) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Unidad no encontrada'
                ]);
            }

            $trainingUnit->updateWithService($request);

            return response()->json([
                'status' => 200,
                'training_unit' => TrainingUnit::getTrainingUnit($trainingUnit->id, $mainCompanyId)
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getTrainingUnit($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $trainingUnit = TrainingUnit::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if (!$trainingUnit) {
            return response()->json([
                'status' => 404,
                'message' => 'Unidad no encontrada'
            ]);
        }

        if ($trainingUnit) {
            return response()->json([
                'status' => 200,
                'training_unit' => $trainingUnit
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Unidad formativa no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $trainingUnit = TrainingUnit::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$trainingUnit) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Unidad no encontrada'
                    ]);
                }

                TrainingUnit::destroy($id);
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

    public function count(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        return TrainingUnit::FilterMainCompany($mainCompanyId)->count();
    }
}

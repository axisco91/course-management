<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\TrainingContractIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingContractIncidenceController extends BaseController
{
    public function trainingContractIncidences($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            return TrainingContractIncidence::getTrainingContractIncidences($id, $mainCompanyId);
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

            $incidence = TrainingContractIncidence::createWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence' => TrainingContractIncidence::getTrainingContractIncidence($incidence->id, $mainCompanyId)
        ]);
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $incidence = TrainingContractIncidence::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$incidence){
                return response()->json([
                    'status' => 404,
                    'message' => 'Incidencia no existe'
                ]);
            }

            $incidence->updateWithService($request);

            return response()->json([
                'status' => 200,
                'incidence' => TrainingContractIncidence::getTrainingContractIncidence($incidence->id, $mainCompanyId)
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getTrainingContractIncidence($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());


        $incidence = TrainingContractIncidence::where('id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if (!$incidence){
            return response()->json([
                'status' => 404,
                'message' => 'Incidencia no existe'
            ]);
        }
        if ($incidence) {
            return response()->json([
                'status' => 200,
                'incidence' => $incidence
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'incidences no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $incidence = TrainingContractIncidence::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$incidence){
                    return response()->json([
                        'status' => 404,
                        'message' => 'Incidencia no existe'
                    ]);
                }

                TrainingContractIncidence::destroy($id);
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

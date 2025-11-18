<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\AdvisorObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvisorObservationController extends BaseController
{

    public function index($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return AdvisorObservation::getAdvisorObservations($id, $mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request){
        try {
            $data = $request->all();
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data['main_company_id'] = $mainCompanyId;

            $observation = AdvisorObservation::createWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'observation' => $observation
        ]);
    }

    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $advisorObservation = AdvisorObservation::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$advisorObservation) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Observación no encontrada'
                ]);
            }
            $data = $request->all();
            $advisorObservation->updateWithService($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'observation' => $advisorObservation
        ]);
    }

    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $observation = AdvisorObservation::where('advisor_observations.id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if ($observation) {
            return response()->json([
                'status' => 200,
                'observation' => $observation
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'observación no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                AdvisorObservation::destroy($id);
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

<?php

namespace App\Http\Controllers\Api;
use App\Models\AdvisorObservation;
use App\Services\AdvisorObservationService;
use Illuminate\Http\Request;

class AdvisorObservationController extends BaseController
{
    private $advisorObservationService;
    public function __construct(AdvisorObservationService $advisorObservationService){
        $this->advisorObservationService = $advisorObservationService;
    }
    public function index($id) {
        try {
            return AdvisorObservation::getAdvisorObservations($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request){
        try {
            $data = $request->all();
            $observation = $this->advisorObservationService->create($data);
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
            $advisorObservation = AdvisorObservation::find($id);
            $data = $request->all();
            $observation = $this->advisorObservationService->update($advisorObservation, $data);
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

    public function show($id){
        $observation = AdvisorObservation::find($id);
        if ($observation) {
            return response()->json([
                'status' => 200,
                'observation' => $observation
            ]);
        }
        return response()->json([
            'status' => 400,
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

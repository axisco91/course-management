<?php

namespace App\Http\Controllers\API;
use App\Models\CompanyObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyObservationController extends BaseController
{
    public function companyObservations($id) {
        try {
            return CompanyObservation::getCompanyObservations($id);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        try {
            $observation = CompanyObservation::createCompanyObservation($request);
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

    public function edit($id, Request $request){
        try {
            $observation = CompanyObservation::updateCompanyObservation($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'observation' => $observation
        ]);
    }

    public function getCompanyObservation($id){
        $observation = CompanyObservation::find($id);
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
                CompanyObservation::destroy($id);
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
}

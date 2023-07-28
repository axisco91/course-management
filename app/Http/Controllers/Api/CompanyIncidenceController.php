<?php

namespace App\Http\Controllers\Api;
use App\Models\CompanyIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyIncidenceController extends BaseController
{
    public function companyIncidences($id) {
        try {
            return CompanyIncidence::getCompanyIncidences($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $incidence = CompanyIncidence::createCompanyIncidence($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence' => CompanyIncidence::getCompanyIncidence($incidence->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $incidence = CompanyIncidence::updateCompanyIncidence($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence' => CompanyIncidence::getCompanyIncidence($incidence->id)
        ]);
    }

    public function getCompanyIncidence($id){
        $incidence = CompanyIncidence::getCompanyIncidence($id);
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

    public function destroy($id){
        if ($id) {
            try {
                CompanyIncidence::destroy($id);
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

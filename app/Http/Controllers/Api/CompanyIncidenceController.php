<?php

namespace App\Http\Controllers\API;
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
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $incidence = CompanyIncidence::createCompanyIncidence($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence' => $incidence
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $incidence = CompanyIncidence::updateCompanyIncidence($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence' => $incidence
        ]);
    }

    public function getCompanyIncidence($id){
        $incidence = CompanyIncidence::find($id);
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

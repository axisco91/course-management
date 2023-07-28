<?php

namespace App\Http\Controllers\Api;
use App\Models\Company;
use App\Models\PotentialCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PotentialCompanyController extends BaseController
{
    public function getPotentialCompanies() {
        try {
            return PotentialCompany::getPotentialCompanies();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $company = PotentialCompany::createPotentialCompany($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'potential_company' => $company
        ]);
    }

    public function edit($id, Request $request){
        try {
            $company = PotentialCompany::updatePotentialCompany($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'potential_company' => $company
        ]);
    }

    public function getPotentialCompany($id){
        $company = PotentialCompany::find($id);
        if ($company) {
            return response()->json([
                'status' => 200,
                'potential_company' => $company
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Empresa no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                PotentialCompany::destroy($id);
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

    public function count(){
        return PotentialCompany::count();
    }

    public function convertCompany($id, Request $request) {
        try {
            Company::createCompany($request);
            $potential_company = PotentialCompany::find($id);
            $potential_company->update([
                'converted' => 1
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'company' => $potential_company
        ]);
    }
}

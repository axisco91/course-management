<?php

namespace App\Http\Controllers\API;
use App\Models\CompanyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyTypeController extends BaseController
{
    public function companyTypes() {
        try {
            return CompanyType::getCompanyTypes();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $company_type = CompanyType::createCompanyType($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'company_type' => $company_type
        ]);
    }

    public function edit($id, Request $request){
        try {
            $company_type = CompanyType::updateCompanyType($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'company_type' => $company_type
        ]);
    }

    public function getCompanyType($id){
        $company_type = CompanyType::find($id);
        if ($company_type) {
            return response()->json([
                'status' => 200,
                'company_type' => $company_type
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Tipo no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CompanyType::destroy($id);
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
        return CompanyType::count();
    }
}

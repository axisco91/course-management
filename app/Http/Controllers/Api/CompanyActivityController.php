<?php

namespace App\Http\Controllers\API;
use App\Models\CompanyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyActivityController extends BaseController
{
    public function companyActivities() {
        try {
            return CompanyActivity::getCompanyActivities();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $company_activity = CompanyActivity::createCompanyActivity($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'company_activity' => $company_activity
        ]);
    }

    public function edit($id, Request $request){
        try {
            $company_activity = CompanyActivity::updateCompanyActivity($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'company_activity' => $company_activity
        ]);
    }

    public function getCompanyActivity($id){
        $company_activity = CompanyActivity::find($id);
        if ($company_activity) {
            return response()->json([
                'status' => 200,
                'company_activity' => $company_activity
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Actividad no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                CompanyActivity::destroy($id);
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
        return CompanyActivity::count();
    }
}

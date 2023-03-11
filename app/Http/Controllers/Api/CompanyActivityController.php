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
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $company_activity = CompanyActivity::createCompanyActivity($data);
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
        $data = json_decode($request->getContent(), true);
        try {
            $company_activity = CompanyActivity::updateCompanyActivity($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
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
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

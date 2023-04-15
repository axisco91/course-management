<?php

namespace App\Http\Controllers\API;
use App\Models\Company;
use App\Models\Course;
use App\Models\Provider;
use App\Models\TrainingAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProviderController extends BaseController
{
    public function providers() {
        try {
            return Provider::getProviders();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $company = Company::createCompany($data);
            $data['company_id'] = $company->id;
            $provider = Provider::createProvider($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'provider' => Provider::getProvider($provider->id)
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $company = Company::updateCompany($data['company_id'], $data);
            $provider = Provider::updateProvider($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'provider' => Provider::getProvider($provider->id)
        ]);
    }

    public function getProvider($id){
        $provider = Provider::getProvider($id);
        if ($provider) {
            return response()->json([
                'status' => 200,
                'provider' => $provider
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Proveedor no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Provider::destroy($id);
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

    public function getTrainingActions($id){
        return TrainingAction::getProviderTrainingActions($id);
    }

    public function count(){
        return Provider::count();
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Company;
use App\Models\Provider;
use App\Models\TrainingAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends BaseController
{
    public function providers(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return Provider::getProviders($mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $company = Company::createWithService($data);
            if ($company) {
                $data['company_id'] = $company->id;
            }
            $provider = Provider::createWithService($data);

            return response()->json([
                'status' => 200,
                'provider' => Provider::getProvider($provider->id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $company = Company::where('id', $request->company_id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$company) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa no encontrada'
                ]);
            }

            $company->updateWithService($request);

            $provider = Provider::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$provider) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Proveedor no encontrada'
                ]);
            }

            $provider->updateWithService($id, $request);

            return response()->json([
                'status' => 200,
                'provider' => Provider::getProvider($provider->id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProvider($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $provider = Provider::getProvider($id, $mainCompanyId);

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

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $provider = Provider::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$provider) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Proveedor no encontrada'
                    ]);
                }

                Provider::destroy($id);
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

    public function getTrainingActions($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return TrainingAction::getProviderTrainingActions($id, $mainCompanyId);
    }

    public function count(Request $request ){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return Provider::FilterMainCompany($mainCompanyId)->count();
    }
}

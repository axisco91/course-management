<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Company;
use App\Models\MainCompany;
use App\Models\PotentialCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PotentialCompanyController extends BaseController
{
    public function getPotentialCompanies(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            return PotentialCompany::getPotentialCompanies($mainCompanyId);
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

            $company = PotentialCompany::createWithService($data);

            return response()->json([
                'status' => 200,
                'potential_company' => $company
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

    public function getPotentialCompany($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $company = PotentialCompany::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();


        if ($company) {
            return response()->json([
                'status' => 200,
                'potential_company' => $company
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Empresa no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $company = PotentialCompany::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$company) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa no existe'
                    ]);
                }

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

    public function count(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        return PotentialCompany::FilterMainCompany($mainCompanyId)->count();
    }

    public function convertCompany($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $potentialCompany = PotentialCompany::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$potentialCompany) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa no existe'
                ]);
            }

            Company::createWithService($request);

            $potentialCompany->updateWithService();

            return response()->json([
                'status' => 200,
                'company' => $potentialCompany
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }


    }

    public function sendEmail(Request $request){
        if ($request['email']){
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $mainCompany = MainCompany::find($mainCompanyId);

                Mail::getSwiftMailer()
                    ->getTransport()
                    ->setUsername('zona@avzformacion.com')
                    ->setPassword('Avz.2021');
                Mail::to($request['email'])->send(new \App\Mail\PotentialCompany());
                return response()->json([
                    'status' => 200
                ]);
            } catch(\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return response()->json([
            'status' => 400,
            'message' => 'Error al enviar correo'
        ]);
    }
}

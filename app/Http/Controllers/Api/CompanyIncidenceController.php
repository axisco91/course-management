<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\CompanyIncidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyIncidenceController extends BaseController
{
    public function companyIncidences($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return CompanyIncidence::getCompanyIncidences($id, $mainCompanyId);
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

            $incidence = CompanyIncidence::createWithService($data);

            return response()->json([
                'status' => 200,
                'incidence' => CompanyIncidence::getCompanyIncidence($incidence->id, $mainCompanyId),
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

            $incidence = CompanyIncidence::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if (!$incidence) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Incidencia no encontrada'
                ]);
            }

            $data = $request->all();

            $incidence = $incidence->updateWithService($data);

            return response()->json([
                'status' => 200,
                'incidence' => CompanyIncidence::getCompanyIncidence($incidence->id, $mainCompanyId)
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCompanyIncidence($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $incidence = CompanyIncidence::getCompanyIncidence($id, $mainCompanyId);
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

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $incidence = CompanyIncidence::where('id', $id)
                    ->FilterMainCompanyId($mainCompanyId)
                    ->first();
                if (!$incidence) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Incidencia no encontrada'
                    ]);
                }

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

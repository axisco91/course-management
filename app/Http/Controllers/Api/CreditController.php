<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends BaseController
{
    public function getCredits($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            return Credit::getCredits($id, $mainCompanyId);
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

            $credit = Credit::createWithService($data);

            return response()->json([
                'status' => 200,
                'credit' => Credit::getCredit($credit->id, $mainCompanyId),
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

            $credit = Credit::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$credit) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Credito no encontrado'
                ]);
            }
            $data = $request->all();

            $credit = $credit->updateWithService($data);

            return response()->json([
                'status' => 200,
                'credit' => Credit::getCredit($credit->id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $credit = Credit::where('id', $id)
                    ->FilterMainCompanyId($mainCompanyId)
                    ->first();

                if (!$credit) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Credito no encontrado'
                    ]);
                }

                Credit::destroy($id);
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

        return Credit::FilterMainCompany($mainCompanyId)->count();
    }
}

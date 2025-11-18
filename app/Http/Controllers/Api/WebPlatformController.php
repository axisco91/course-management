<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\WebPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebPlatformController extends BaseController
{
    public function webPlatforms(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            return WebPlatform::getWebPlatforms($mainCompanyId);
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

            $web = WebPlatform::createWithService($data);

            return response()->json([
                'status' => 200,
                'web_platform' => WebPlatform::getWebPlatform($web->id, $mainCompanyId),
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

            $web = WebPlatform::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$web) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Plataforma web no encontrado'
                ]);
            }

            $web = WebPlatform::updateWithService($request);

            return response()->json([
                'status' => 200,
                'web_platform' => WebPlatform::getWebPlatform($web->id, $mainCompanyId),
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getWebPlatform($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $web = WebPlatform::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if (!$web) {
            return response()->json([
                'status' => 404,
                'message' => 'Plataforma web no encontrado'
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Plataforma no existe'
        ]);
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $web = WebPlatform::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$web) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Plataforma web no encontrado'
                    ]);
                }

                WebPlatform::destroy($id);
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

        return WebPlatform::where('main_company_id', $mainCompanyId)->count();
    }
}

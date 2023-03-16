<?php

namespace App\Http\Controllers\API;
use App\Models\Tutoring;
use App\Models\WebPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WebPlatformController extends BaseController
{
    public function webPlatforms() {
        try {
            return WebPlatform::getWebPlatforms();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $web = WebPlatform::createProfitability($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'web_platform' => $web
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $web = WebPlatform::updateUser($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'web_platform' => $web
        ]);
    }

    public function getWebPlatform($id){
        $web = WebPlatform::find($id);
        if ($web) {
            return response()->json([
                'status' => 200,
                'web_platform' => $web
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Plataforma no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                WebPlatform::destroy($id);
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

    public function count(){
        return WebPlatform::count();
    }
}

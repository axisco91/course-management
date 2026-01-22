<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\WebPlatformResource;
use App\Models\WebPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebPlatformController extends BaseController
{
    public function webPlatforms(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = WebPlatform::getWebPlatform($mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $webPlatforms = WebPlatformResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'web_platforms' => $webPlatforms,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $webPlatforms = WebPlatformResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'web_platforms' => $webPlatforms,
                ],
                trans('Obtenido con éxito')
            );
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

            return $this->sendResponse(
                [
                    'web_platform' => $web,
                ],
                trans('Creado con éxito')
            );
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

            $web->updateWithService($request->all());

            return $this->sendResponse(
                [
                    'web_platform' => WebPlatform::getWebPlatform($mainCompanyId)->where('web_platforms.id', $web->id)->first(),
                ],
                trans('Guardado con éxito')
            );
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

        return $this->sendResponse(
            [
                'web_platform' => $web,
            ],
            trans('Obtenido con éxito')
        );
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
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
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

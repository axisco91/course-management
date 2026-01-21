<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CreditResource;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends BaseController
{
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = Credit::getCredit($request->company_id, $mainCompanyId);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $credits = CreditResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'credits' => $credits,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $credits = CreditResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'credits' => $credits,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id, Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            // Si tu scope ya filtra por company y mainCompany, perfecto:
            $credit = Credit::getCredit($request->company_id, $mainCompanyId)
                ->where('credits.id', $id)
                ->first();

            if (!$credit) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Crédito no existe'
                ], 404);
            }

            return $this->sendResponse(
                [
                    'credit' => new CreditResource($credit),
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $credit = Credit::createWithService($data);

            return $this->sendResponse(
                [
                    'credit' => Credit::getCredit($credit->company_id, $mainCompanyId)->where('credits.id', $credit->id)->first(),
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

            return $this->sendResponse(
                [
                    'credit' => Credit::getCredit($credit->company_id, $mainCompanyId)->where('credits.id', $credit->id)->first(),
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

    public function destroy($id, Request $request){
        if ($id) {
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

                Credit::destroy($id);
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
}

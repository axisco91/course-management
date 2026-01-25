<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProviderResource;
use App\Models\Company;
use App\Models\Provider;
use App\Models\TrainingAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends BaseController
{
    public function providers(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = Provider::getProvider($mainCompanyId);

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=consultoria
            if ($request->filled('search')) {
                $search = trim($request->input('search'));

                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ SORT (por defecto name asc)
            // ?sort=name   -> asc
            // ?sort=-name  -> desc
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // whitelist de columnas ordenables
            $allowedSorts = ['id', 'name', 'created_at', 'updated_at'];
            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $providers = ProviderResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'providers' => $providers,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $providers = ProviderResource::collection($query->get());

            return $this->sendResponse(
                [
                    'providers' => $providers,
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

            $company = Company::createWithService($data);
            if ($company) {
                $data['company_id'] = $company->id;
            }
            $provider = Provider::createWithService($data);

            return $this->sendResponse(
                [
                    'provider' => Provider::getProvider($mainCompanyId)->where('providers.id', $provider->id)->first(),
                ],
                trans('Created con éxito')
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

            return $this->sendResponse(
                [
                    'provider' => Provider::getProvider($mainCompanyId)->where('providers.id', $provider->id)->first(),
                ],
                trans('Guardar con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProvider($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $provider = Provider::getProvider($mainCompanyId)->where('providers.id', $id)->first();

        if ($provider) {
            return $this->sendResponse(
                [
                    'provider' => $provider,
                ],
                trans('Obtenido con éxito')
            );
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

    public function getTrainingActions($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return TrainingAction::getProviderTrainingActions($id, $mainCompanyId)->get();
    }

    public function count(Request $request ){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return Provider::FilterMainCompany($mainCompanyId)->count();
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\MainCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MainCompanyController extends BaseController
{

    /**
     * Obtenemos empresas principales
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            $query = MainCompany::query();

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('phone')) {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }
            if ($request->filled('url')) {
                $query->where('url', 'like', '%' . $request->url . '%');
            }
            if ($request->filled('active')) {
                $query->where('active', (int) $request->active);
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'main_companies' => $paginator->items(),
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $mainCompanies = $query->get();

            return $this->sendResponse(
                [
                    'main_companies' => $mainCompanies,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenemos una empresa principal
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $mainCompany = MainCompany::find($id);

            if (!$mainCompany) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa principal no existe'
                ], 404);
            }

            return $this->sendResponse(
                ['main_company' => $mainCompany],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualizar empresa principal por id
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateById($id, Request $request)
    {
        try {
            $mainCompany = MainCompany::find($id);

            if (!$mainCompany) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa principal no existe'
                ], 404);
            }

            $mainCompany->updateWithService($request->all());

            return $this->sendResponse(
                ['main_company' => $mainCompany->fresh()],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function basic(Request $request) {
        $mainCompany = null;

        $candidates = array_filter([
            $this->normalizeHostname($request->input('hostname')),
            $this->normalizeHostname($request->headers->get('origin')),
            $this->normalizeHostname($request->headers->get('referer')),
            $this->normalizeHostname($request->getHost()),
        ]);
        $candidates = array_values(array_unique($candidates));

        Log::info('Candidates: ' . implode(', ', $candidates));
        if (!empty($candidates)) {
            $placeholders = implode(',', array_fill(0, count($candidates), '?'));
            $mainCompany = MainCompany::query()
                ->whereRaw(
                    "LOWER(TRIM(BOTH '/' FROM REPLACE(REPLACE(TRIM(url), 'https://', ''), 'http://', ''))) IN ($placeholders)",
                    $candidates
                )
                ->first();
        }

        if (!$mainCompany) {
            Log::warning('No main company found');
            $mainCompany = MainCompany::find(1);
        }

        return $this->sendResponse(
            ['main_company' => $mainCompany],
            trans('success.Retrieved successfully')
        );
    }

    public function update(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $mainCompany = MainCompany::find($mainCompanyId);
            if (!$mainCompany) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa principal no existe'
                ], 404);
            }

            $mainCompany->updateWithService($request->all());

            return $this->sendResponse(
                ['main_company' => $mainCompany->fresh()],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function activate(Request $request)
    {
        return $this->setActiveStatus($request, true);
    }

    public function deactivate(Request $request)
    {
        return $this->setActiveStatus($request, false);
    }

    private function setActiveStatus(Request $request, bool $isActive)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $mainCompany = MainCompany::find($mainCompanyId);
            if (!$mainCompany) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Empresa principal no existe'
                ], 404);
            }

            $mainCompany->updateWithService([
                'active' => $isActive ? 1 : 0
            ]);

            return $this->sendResponse(
                ['main_company' => $mainCompany->fresh()],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    private function normalizeHostname(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
            $value = 'https://' . $value;
        }

        $host = parse_url($value, PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return null;
        }

        return strtolower($host);
    }

    /**
     * Crear empresa principal
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        try {
            $mainCompany = MainCompany::createWithService($request->all());

            return $this->sendResponse(
                [
                    'main_company' => $mainCompany,
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

    /**
     * Eliminar empresa principal
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
                $mainCompany = MainCompany::find($id);
                if (!$mainCompany) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Empresa principal no existe'
                    ]);
                }

                $mainCompany->deleteWithService();

                return $this->sendResponse([]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

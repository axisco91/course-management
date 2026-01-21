<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Center;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CenterController extends BaseController
{

    /**
     * Obtener centros
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCenters(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            // Query base
            $query = Center::getCenter($mainCompanyId);

            // Si quieres, aquí podrías añadir filtros por nombre, etc.

            // === CON PAGINACIÓN ===
            if ($request->filled('perPage')) {
                $perPage   = (int) $request->perPage;
                $paginator = $query->paginate($perPage);

                // Colección de la página actual
                $centers = $paginator->getCollection();

                // Marcamos "used" sin cambiar la forma de la paginación
                $centers->transform(function ($center) use ($mainCompanyId) {
                    $used = Course::where(function ($q) use ($center) {
                        $q->where('delivery_center_id', $center->id)
                            ->orWhere('formation_center_id', $center->id);
                    })
                        ->FilterMainCompany($mainCompanyId)
                        ->exists();

                    $center->used = $used;

                    return $center;
                });

                // Volvemos a meter la colección modificada al paginator
                $paginator->setCollection($centers);

                // Si tienes un Resource para los centers
                // $centersResource = CenterResource::collection($paginator);
                $centersResource = $centers; // o usa el resource si lo tienes

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'centers' => $centersResource,
                        'links'   => $paginationData['links'],
                        'meta'    => $paginationData['meta'],
                    ],
                    trans('Obtenido')
                );
            }

            // === SIN PAGINACIÓN ===
            $centers = $query->get();

            $centers->transform(function ($center) use ($mainCompanyId) {
                $used = Course::where(function ($q) use ($center) {
                    $q->where('delivery_center_id', $center->id)
                        ->orWhere('formation_center_id', $center->id);
                })
                    ->FilterMainCompany($mainCompanyId)
                    ->exists();

                $center->used = $used;

                return $center;
            });

            //$centersResource = CenterResource::collection($centers);
            $centersResource = $centers; // o Resource

            return $this->sendResponse(
                [
                    'centers' => $centersResource,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCenter(int $id, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
            $request->headers->get('origin'),
            Auth::id()
        );

        $center = Center::getCenter($mainCompanyId)
            ->where('id', $id)
            ->first();

        if (! $center) {
            return response()->json([
                'success' => false,
                'message' => 'Centro no existe',
            ], 404);
        }

        // Comprobamos si el centro está usado en algún curso
        $used = Course::where(function ($q) use ($center) {
            $q->where('delivery_center_id', $center->id)
                ->orWhere('formation_center_id', $center->id);
        })
            ->FilterMainCompany($mainCompanyId)
            ->exists();

        $center->used = $used;

        // Si quieres usar tu sendResponse para ser consistente:
        return $this->sendResponse(
            [
                'center' => $center,
            ],
            trans('Obtenido con éxito')
        );
    }


    /**
     * Crear
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            // Aquí idealmente iría un $request->validate([...])

            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            // Crea el centro mediante tu servicio
            $element = Center::createWithService($data);

            // Recuperamos el centro con el mismo scope que usas en getCenter
            $center = Center::getCenter($mainCompanyId)
                ->where('id', $element->id)
                ->first();

            // Si por lo que sea no se encontrara (raro, pero por seguridad)
            if (! $center) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha podido recuperar el centro recién creado',
                ], 500);
            }

            // Comprobamos si el centro está usado en algún curso
            $used = Course::where(function ($q) use ($center) {
                $q->where('delivery_center_id', $center->id)
                    ->orWhere('formation_center_id', $center->id);
            })
                ->FilterMainCompany($center->main_company_id ?? $mainCompanyId)
                ->exists();

            $center->used = $used;

            // Usamos tu helper sendResponse para mantener consistencia
            return $this->sendResponse(
                [
                    'center' => $center,
                ],
                trans('Creado con éxito'),
                201
            );
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Editar
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request){
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();

            $center = Center::where('centers.id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            $element = $center->updateWithService($data);
            $center = Center::getCenter($mainCompanyId)
                ->where('id', $element->id)
                ->first();

            if ($center) {
                $course = Course::orWhere('delivery_center_id', $center['id'])
                    ->orWhere('formation_center_id', $center['id'])
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if ($course){
                    $center['used'] = true;
                } else {
                    $center['used'] = false;
                }
            }
            return $this->sendResponse(
                [
                    'center' => $center,
                ],
                trans('Actualizado con éxito'),
                200
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $center = Center::where('id', $id)
                    ->FilterMainCompany($mainCompanyId);

                if (!$center) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Centro no existe'
                    ]);
                }

                Center::destroy($id);
                return $this->sendResponse(
                    [],
                    trans('Elimiando con éxito')
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

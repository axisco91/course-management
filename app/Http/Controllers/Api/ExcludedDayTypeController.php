<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Http\Resources\ExcludedDayTypeResource;
use App\Models\ExcludedDayType;
use Illuminate\Http\Request;

class ExcludedDayTypeController extends BaseController
{
    public function getExcludedDayTypes(Request $request) {
        try {
            $query = ExcludedDayType::select('excluded_day_types.*', 'id as value', 'name as label');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $excludedDayTypes = ExcludedDayTypeResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'excluded_day_types' => $excludedDayTypes,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $excludedDayTypes = ExcludedDayTypeResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'excluded_day_types' => $excludedDayTypes,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

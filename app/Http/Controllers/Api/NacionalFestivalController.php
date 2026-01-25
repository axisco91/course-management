<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\NacionalFestivalResource;
use App\Models\NacionalFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NacionalFestivalController extends BaseController
{
    public function getNacionalFestivals(Request $request)
    {
        try {
            $query = NacionalFestival::query();

            // ✅ FILTRO POR NOMBRE
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ FILTRO POR DAY
            // ?day=25-12-2024  o  ?day=2024-12-25
            if ($request->filled('day')) {
                try {
                    // intenta d-m-Y
                    $day = Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d');
                } catch (\Exception $e) {
                    // fallback ISO / Y-m-d
                    $day = Carbon::parse($request->day)->format('Y-m-d');
                }

                $query->whereDate('day', $day);
            }

            // ✅ SORT (por defecto name asc)
            // ?sort=name   -> asc
            // ?sort=-name  -> desc
            // ?sort=day    -> asc
            // ?sort=-day   -> desc
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // 🔒 whitelist columnas ordenables
            $allowedSorts = ['id', 'name', 'day', 'created_at', 'updated_at'];
            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $nacionalFestivals = NacionalFestivalResource::collection($paginator);
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'nacional_festivals' => $nacionalFestivals,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $nacionalFestivals = NacionalFestivalResource::collection($query->get());

            return $this->sendResponse(
                [
                    'nacional_festivals' => $nacionalFestivals,
                ],
                trans('Obtenido con éxito')
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function create(Request $request)
    {
        try {
            $day = null;

            if ($request->filled('day')) {
                try {
                    // Intentamos d-m-Y (frontend)
                    $day = Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Fallback: Y-m-d o ISO
                    $day = Carbon::parse($request->day)->format('Y-m-d');
                }
            }

            $festival = NacionalFestival::create([
                'name' => $request->name,
                'day'  => $day
            ]);

            return $this->sendResponse(
                ['nacional_festival' => $festival],
                trans('Creado con éxito')
            );

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, Request $request){
        try {
            $festival = NacionalFestival::find($id);

            $day = null;

            if ($request->filled('day')) {
                try {
                    // Intentamos d-m-Y (frontend)
                    $day = Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Fallback: Y-m-d o ISO
                    $day = Carbon::parse($request->day)->format('Y-m-d');
                }
            }

            $festival->update([
                'name' => $request->name,
                'day' => $day
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'nacional_festival' => $festival,
            ],
            trans('Guardado con éxito')
        );
    }

    public function show($id){
        try {
            $festival = NacionalFestival::find($id);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'nacional_festival' => $festival,
            ],
            trans('Guardado con éxito')
        );
    }

    public function destroy($id){
        if ($id) {
            try {
                NacionalFestival::destroy($id);
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

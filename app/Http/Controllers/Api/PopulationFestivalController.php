<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\PopulationFestivalResource;
use App\Models\PopulationFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PopulationFestivalController extends BaseController
{
    public function getPopulationFestivals(Request $request) {
        try {
            $query = PopulationFestival::select('population_festivals.*', 'populations.name as population')
                ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $populationFestivals = PopulationFestivalResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'population_festivals' => $populationFestivals,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $populationFestivals = PopulationFestivalResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'population_festivals' => $populationFestivals,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPopulationFestival($id) {
        try {
            $populationFestival = PopulationFestival::select('population_festivals.*', 'populations.name as population')
                ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id')
                ->where('population_festivals.id', $id)
                ->first();

            return $this->sendResponse(
                [
                    'population_festival' => $populationFestival,
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
            $festival = PopulationFestival::create([
                'name' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d'),
                'population_id' => $request->population_id
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        $populationFestival = PopulationFestival::select('population_festivals.*', 'populations.name as population')
            ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id')
            ->where('population_festivals.id', $festival->id)
            ->first();

        return $this->sendResponse(
            [
                'population_festival' => $populationFestival,
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $festival = PopulationFestival::find($id);
            $festival->update([
                'name' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d'),
                'population_id' => $request->population_id
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        $populationFestival = PopulationFestival::select('population_festivals.*', 'populations.name as population')
            ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id')
            ->where('population_festivals.id', $id)
            ->first();

        return $this->sendResponse(
            [
                'population_festival' => $populationFestival,
            ],
            trans('Guardado con éxito')
        );
    }

    public function destroy($id){
        if ($id) {
            try {
                PopulationFestival::destroy($id);

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

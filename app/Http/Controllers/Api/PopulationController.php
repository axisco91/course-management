<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\PopulationResource;
use App\Models\Population;
use Illuminate\Http\Request;

class PopulationController extends BaseController
{
    public function populations(Request $request) {
        try {
            $query = Population::select('populations.*', 'populations.id as value', 'populations.name as label');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $populations = PopulationResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'populations' => $populations,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $populations = PopulationResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'populations' => $populations,
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
            $population = Population::create([
                'name' => $request->name
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'population' => Population::select('populations.*', 'populations.id as value', 'populations.name as label')->where('id', $population->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $population = Population::find($id);
            $population->update([
               'name' => $request->name]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'population' => $population,
            ],
            trans('Guardado con éxito')
        );
    }

    public function getPopulation($id){
        $population = Population::find($id);
        if ($population) {
            return $this->sendResponse(
                [
                    'population' => $population,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Plataforma no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Population::destroy($id);
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

    public function populationsWithFestivals(Request $request) {
        try {
            if ($request){
                return $this->sendResponse(
                    [
                        'populations' => Population::populationsWithFestivals($request->beginning, $request->end)->get(),
                    ],
                    trans('Creado con éxito')
                );
            }

            return $this->sendResponse(
                [
                    'populations' => Population::getPopulationsWithFestivals()->get(),
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }}

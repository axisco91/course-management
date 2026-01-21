<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\ProvinceFestivalResource;
use App\Models\ProvinceFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProvinceFestivalController extends BaseController
{
    public function getProvinceFestivals(Request $request) {
        try {
            $query = ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $provinceFestivals = ProvinceFestivalResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'province_festivals' => $provinceFestivals,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $provinceFestivals = ProvinceFestivalResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'province_festivals' => $provinceFestivals,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProvinceFestival($id) {
        try {
            return $this->sendResponse(
                [
                    'province_festival' => ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                        ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                        ->where('province_festivals.id', $id)
                        ->first(),
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
            $festival = ProvinceFestival::create([
                'name' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d'),
                'province_id' => $request->province_id
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'province_festival' => ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                    ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                    ->where('province_festivals.id', $festival->id)
                    ->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function edit($id, Request $request){
        try {
            $festival = ProvinceFestival::find($id);
            $festival->update([
                'name' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d'),
                'province_id' => $request->province_id
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'province_festival' => ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                    ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                    ->where('province_festivals.id', $id)
                    ->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function destroy($id){
        if ($id) {
            try {
                ProvinceFestival::destroy($id);
                return $this->sendResponse(
                    [
                        'province_festival' => ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                            ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                            ->where('province_festivals.id', $id)
                            ->first(),
                    ],
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

<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CommunityFestivalResource;
use App\Models\CommunityFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommunityFestivalController extends BaseController
{
    public function index(Request $request) {
        try {
            $query = CommunityFestival::select('community_festivals.*', 'communities.name as community')
                ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $communityFestivals = CommunityFestivalResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'community_festivals' => $communityFestivals,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $communityFestivals = CommunityFestivalResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'community_festivals' => $communityFestivals,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id) {
        try {
            return $this->sendResponse(
                [
                    'community_festival' => CommunityFestival::select('community_festivals.*', 'communities.name as community')
                        ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id')
                        ->where('community_festivals.id', $id)
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

    public function store(Request $request){
        try {
            $festival = CommunityFestival::create([
                'name' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d'),
                'community_id' => $request->community_id
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'community_festival' => CommunityFestival::select('community_festivals.*', 'communities.name as community')
                    ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id')
                    ->where('community_festivals.id', $festival->id)
                    ->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function update($id, Request $request){
        try {
            $festival = CommunityFestival::find($id);
            $festival->update([
                'name' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d'),
                'community_id' => $request->community_id
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'community_festival' => CommunityFestival::select('community_festivals.*', 'communities.name as community')
                    ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id')
                    ->where('community_festivals.id', $festival->id)
                    ->first(),
            ],
            trans('Guardado con éxito')
        );
    }

    public function destroy($id){
        if ($id) {
            try {
                CommunityFestival::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends BaseController
{
    public function index(Request $request) {
        try {
            \Log::info(Community::select('communities.*', 'communities.id as value', 'communities.name as label')->get());

            $query = Community::select('communities.*', 'communities.id as value', 'communities.name as label');

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $communities = CommunityResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'communities' => $communities,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $communities = CommunityResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'communities' => $communities,
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
            $community = Community::create([
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
                'community' => Community::select('communities.*', 'communities.id as value', 'communities.name as label')->where('id', $community->id)->first(),
            ],
            trans('Creado con éxito')
        );
    }

    public function update($id, Request $request){
        try {
            $community = Community::find($id);
            if ($community) {
                $community->update([
                    'name' => $request->name
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return $this->sendResponse(
            [
                'community' => $community,
            ],
            trans('Guardado con éxito')
        );
    }

    public function show($id){
        $community = Community::find($id);
        if ($community) {
            return $this->sendResponse(
                [
                    'community' => $community,
                ],
                trans('Obtenido con éxito')
            );
        }
        return $this->sendResponse(
            [
                'community' => $community,
            ],
            trans('Creado con éxito')
        );
    }

    public function destroy($id){
        if ($id) {
            try {
                Community::destroy($id);
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

    public function communitiesWithFestivals(Request $request) {
        try {
            if ($request){
                return $this->sendResponse(
                    [
                        'communities' => Community::communitiesWithFestivals($request->beginning, $request->end)->get(),
                    ],
                    trans('Creado con éxito')
                );
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }}

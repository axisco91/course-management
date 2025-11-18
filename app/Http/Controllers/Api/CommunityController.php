<?php

namespace App\Http\Controllers\Api;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommunityController extends BaseController
{
    public function index() {
        try {
            \Log::info(Community::select('communities.*', 'communities.id as value', 'communities.name as label')->get());

            return Community::select('communities.*', 'communities.id as value', 'communities.name as label')->get();
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

        return response()->json([
            'status' => 200,
            'community' => Community::select('communities.*', 'communities.id as value', 'communities.name as label')->where('id', $community->id)->first()
        ]);
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

        return response()->json([
            'status' => 200,
            'community' => Community::select('communities.*', 'communities.id as value', 'communities.name as label')->where('id', $id)->first()
        ]);
    }

    public function show($id){
        $community = Community::find($id);
        if ($community) {
            return response()->json([
                'status' => 200,
                'community' => $community
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Comunidad no existe'
        ]);
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
            \Log::info('hi');
            \Log::info(Community::communitiesWithFestivals($request->beginning, $request->end)->get());
            if ($request){

                return Community::communitiesWithFestivals($request->beginning, $request->end)->get();
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }}

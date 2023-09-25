<?php

namespace App\Http\Controllers\Api;
use App\Models\CommunityFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommunityFestivalController extends BaseController
{
    public function index() {
        try {
            return CommunityFestival::select('community_festivals.*', 'communities.name as community')
                ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id')
                ->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id) {
        try {
            return CommunityFestival::select('community_festivals.*', 'communities.name as community')
                ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id')
                ->where('community_festivals.id', $id)
                ->first();
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

        return response()->json([
            'status' => 200,
            'community_festival' => CommunityFestival::select('community_festivals.*', 'communities.name as community')
                ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id')
                ->where('community_festivals.id', $festival->id)
                ->first()
        ]);
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

        return response()->json([
            'status' => 200,
            'community_festival' =>  CommunityFestival::select('community_festivals.*', 'communities.name as community')
                ->leftjoin('communities', 'communities.id', '=', 'community_festivals.community_id')
                ->where('community_festivals.id', $festival->id)
                ->first()
        ]);
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

<?php

namespace App\Http\Controllers\Api;
use App\Models\ProvinceFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProvinceFestivalController extends BaseController
{
    public function getProvinceFestivals() {
        try {
            return ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                ->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProvinceFestival($id) {
        try {
            return ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                ->where('province_festivals.id', $id)
                ->first();
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

        return response()->json([
            'status' => 200,
            'province_festival' => ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                ->where('province_festivals.id', $festival->id)
                ->first()
        ]);
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

        return response()->json([
            'status' => 200,
            'province_festival' => ProvinceFestival::select('province_festivals.*', 'provinces.name as province')
                ->leftjoin('provinces', 'provinces.id', '=', 'province_festivals.province_id')
                ->where('province_festivals.id', $festival->id)
                ->first()
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProvinceFestival::destroy($id);
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

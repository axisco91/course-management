<?php

namespace App\Http\Controllers\API;
use App\Models\PopulationFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PopulationFestivalController extends BaseController
{
    public function getPopulationFestivals() {
        try {
            return PopulationFestival::select('population_festivals.*', 'populations.name as population')
                ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id')
                ->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPopulationFestival($id) {
        try {
            return PopulationFestival::select('population_festivals.*', 'populations.name as population')
                ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id')
                ->where('population_festivals.id', $id)
                ->first();
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

        return response()->json([
            'status' => 200,
            'population_festival' => PopulationFestival::select('population_festivals.*', 'populations.name as population')
                                        ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id')
                                        ->where('population_festivals.id', $festival->id)
                                        ->first()
        ]);
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

        return response()->json([
            'status' => 200,
            'population_festival' =>  PopulationFestival::select('population_festivals.*', 'populations.name as population')
                ->leftjoin('populations', 'populations.id', '=', 'population_festivals.population_id')
                ->where('population_festivals.id', $festival->id)
                ->first()
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                PopulationFestival::destroy($id);
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

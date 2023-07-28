<?php

namespace App\Http\Controllers\Api;
use App\Models\Population;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PopulationController extends BaseController
{
    public function populations() {
        try {
            return Population::select('populations.*', 'populations.id as value', 'populations.name as label')->get();
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

        return response()->json([
            'status' => 200,
            'population' => Population::select('populations.*', 'populations.id as value', 'populations.name as label')->where('id', $population->id)->first()
        ]);
    }

    public function edit($id, Request $request){
        try {
            $population = Population::find($id);
            $population->name = $request->name;
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'population' => Population::select('populations.*', 'populations.id as value', 'populations.name as label')->where('id', $population->id)->first()
        ]);
    }

    public function getPopulation($id){
        $population = Population::find($id);
        if ($population) {
            return response()->json([
                'status' => 200,
                'population' => $population
            ]);
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

    public function populationsWithFestivals(Request $request) {
        try {
            if ($request){
                return Population::populationsWithFestivals($request->beginning, $request->end)->get();
            }
            return Province::getPopulationsWithFestivals()->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }}

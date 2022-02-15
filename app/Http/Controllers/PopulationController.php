<?php

namespace App\Http\Controllers;

use App\Models\Population;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PopulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('populations/list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'nombre' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $population = Population::create([
            'name' => $request['nombre'],
        ]);

        return response()->json([
            'status' => 200,
            'population' => $population
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'nombre' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $population = Population::find($request['id']);

        $population->update([
            'name' => $request['nombre'],
        ]);

        return response()->json([
            'status' => 200,
            'population' => $population
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request)
    {
        $population = Population::find($request['id']);

        $population->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function restPopulations(Request  $request){
        $populations = Population::all();
        $data = [];

        foreach ($populations as $population){
            $info = [
                'name' => $population['name'],
                'accions' => '<a class="btn btn-success btn-sm" id="updatePopulation" data-name="'.$population['name'].'" data-id="'.$population['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger btn-sm" id="deletePopulation" data-id="'.$population['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];

            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}

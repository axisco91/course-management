<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TrainingContractSeries; 
use App\Http\Controllers\Controller;

class TrainingContractSeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TrainingContractSeries::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $series = TrainingContractSeries::create($request->all());
        return response()->json($series, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        return response()->json([
            'status' => 200,
            'series' => $series
        ]);
    }

    

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
       // Busca la instancia de TrainingContractSeries por su ID
       $series = TrainingContractSeries::findOrFail($id);

       // Actualiza la instancia con los datos del request
       $series->update($request->all());

       // Devuelve la respuesta JSON
       return response()->json([
           'status' => 200,
           'series' => $series
       ]);
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        $series->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        return response()->json([
            'status' => 200,
            'series' => $series
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $series = TrainingContractSeries::create($request->all());
        return response()->json([
            'status' => 201,
            'series' => $series
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        $series->update($request->all());
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
    public function destroy($id)
    {
        $series = TrainingContractSeries::findOrFail($id);
        $series->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Series deleted successfully'
        ]);
    }
}
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PotentialTrainingContract;
use Illuminate\Http\Request;

class PotentialTrainingContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $contracts = PotentialTrainingContract::all();
            return response()->json([
                'status' => 200,
                'contracts' => $contracts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $contract = PotentialTrainingContract::create($request->all());
            return response()->json([
                'status' => 201,
                'contract' => $contract
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $contract = PotentialTrainingContract::findOrFail($id);
            return response()->json([
                'status' => 200,
                'contract' => $contract
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        try {
            $contract = PotentialTrainingContract::findOrFail($id);
            $contract->update($request->all());
            return response()->json([
                'status' => 200,
                'contract' => $contract
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $contract = PotentialTrainingContract::findOrFail($id);
            $contract->delete();
            return response()->json([
                'status' => 204,
                'message' => 'Contract deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
}

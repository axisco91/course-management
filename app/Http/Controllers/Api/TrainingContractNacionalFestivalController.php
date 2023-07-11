<?php

namespace App\Http\Controllers\API;
use App\Models\ExcludedDayType;
use App\Models\NacionalFestival;
use App\Models\TrainingActionLevel;
use App\Models\TrainingContractFestival;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractNacionalFestivalController extends BaseController
{
    public function getTrainingContractNacionalFestivals($id) {
        try {
            return TrainingContractFestival::where('training_contract_id', $id)->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $festival = TrainingContractFestival::create([
                'training_contract_id' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d')
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'nacional_festival' => $festival
        ]);
    }

    public function edit($id, Request $request){
        try {
            $festival = NacionalFestival::find($id);
            $festival->update([
                'name' => $request->name,
                'day' => Carbon::createFromFormat('d-m-Y', $request->day)->format('Y-m-d')
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'nacional_festival' => $festival
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                NacionalFestival::destroy($id);
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

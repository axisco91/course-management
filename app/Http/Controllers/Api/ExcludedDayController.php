<?php

namespace App\Http\Controllers\API;
use App\Models\ExcludedDay;
use App\Models\TrainingActionLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ExcludedDayController extends BaseController
{
    public function getExcludedDays() {
        try {
            return ExcludedDay::getExcludedDays();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $excluded_day = ExcludedDay::createExcludedDay($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'excluded_day' => $excluded_day
        ]);
    }

    public function edit($id, Request $request){
        try {
            $excluded_day = ExcludedDay::updateExcludedDay($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'excluded_day' => $excluded_day
        ]);
    }

    public function getExcludedDay($id){
        $excluded_day = ExcludedDay::find($id);
        if ($excluded_day) {
            return response()->json([
                'status' => 200,
                'excluded_day' => $excluded_day
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Día excludido no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ExcludedDay::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return ExcludedDay::count();
    }
}

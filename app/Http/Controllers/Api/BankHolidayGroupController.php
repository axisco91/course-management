<?php

namespace App\Http\Controllers\Api;
use App\Models\ActionType;
use App\Models\BankHolidayGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BankHolidayGroupController extends BaseController
{

    public function getBankHolidayGroups(Request $request) {
        try {
            if ($request) {
                return BankHolidayGroup::getBankHolidayGroup($request['beginning'], $request['end'])->get();
            }
            return BankHolidayGroup::getBankHolidayGroup()->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

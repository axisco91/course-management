<?php

namespace App\Http\Controllers\API;

use App\Models\ExcludedDayType;

class ExcludedDayTypeController extends BaseController
{
    public function getExcludedDayTypes() {
        try {
            return ExcludedDayType::select('excluded_day_types.*', 'id as value', 'name as label')->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

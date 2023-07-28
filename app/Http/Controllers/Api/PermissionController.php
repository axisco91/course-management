<?php

namespace App\Http\Controllers\Api;
use App\Models\ActionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseController
{
    public function permissions() {
        try {
            return Permission::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

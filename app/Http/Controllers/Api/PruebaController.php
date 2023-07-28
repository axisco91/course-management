<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class PruebaController extends BaseController
{

    public function index()
    {

        return response()->json(['status' => 200, 'data' => ['nombre' => 'Francsco']]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('provinces/list');
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

        $province = Province::create([
            'name' => $request['nombre'],
        ]);

        return response()->json([
            'status' => 200,
            'province' => $province
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

        $province = Province::find($request['id']);

        $province->update([
            'name' => $request['nombre'],
        ]);

        return response()->json([
            'status' => 200,
            'population' => $province
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
        $province = Province::find($request['id']);

        $province->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function restProvinces(Request  $request){
        $provinces = Province::all();
        $data = [];

        foreach ($provinces as $province){
            $info = [
                'name' => $province['name'],
                'accions' => '<a class="btn btn-success btn-sm" id="updateProvince" data-name="'.$province['name'].'" data-id="'.$province['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger btn-sm" id="deleteProvince" data-id="'.$province['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];

            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}

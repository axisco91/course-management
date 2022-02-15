<?php

namespace App\Http\Controllers;

use App\Models\Cnae;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CnaeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cnaes/list');
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
            'cnae' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $cnae = Cnae::create([
            'cnae' => $request['cnae'],
        ]);

        return response()->json([
            'status' => 200,
            'province' => $cnae
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
            'cnae' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $cnae = Cnae::find($request['id']);

        $cnae->update([
            'cnae' => $request['cnae'],
        ]);

        return response()->json([
            'status' => 200,
            'population' => $cnae
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
        $cnae = Cnae::find($request['id']);

        $cnae->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function restCnaes(Request  $request){
        $cnaes = Cnae::all();
        $data = [];

        foreach ($cnaes as $cnae){
            $info = [
                'name' => $cnae['cnae'],
                'accions' => '<a class="btn btn-success btn-sm" id="updateProvince" data-cnae="'.$cnae['cnae'].'" data-id="'.$cnae['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger btn-sm" id="deleteProvince" data-id="'.$cnae['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];

            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}

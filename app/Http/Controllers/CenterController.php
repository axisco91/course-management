<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('centers/list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('centers/createCenter');
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

        $center = Center::create([
            'name' => $request['nombre'],
            'address' => $request['address'],
            'email' => $request['email'],
            'telephone' => $request['telephone']
        ]);

        return response()->json([
            'status' => 200,
            'center' => $center
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $center = Center::find($id);

        return view('centers/editCenter', compact('center'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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

        $center = Center::find($request['id']);

        $center->update([
            'name' => $request['nombre'],
            'address' => $request['address'],
            'email' => $request['email'],
            'telephone' => $request['telephone']
        ]);

        return response()->json([
            'status' => 200,
            'center' => $center
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
        $center = Center::find($request['id']);

        $center->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function restCenters(Request  $request){
        $centers = Center::all();
        $data = [];

        foreach ($centers as $center){
            $info = [
                'name' => $center['name'],
                'address' => $center['address'],
                'email' => $center['email'],
                'telephone' => $center['telephone'],
                'accions' => '<a class="btn btn-success btn-sm" href="centers/edit/'.$center['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger btn-sm deleteCenter" data-id="'.$center['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];

            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}

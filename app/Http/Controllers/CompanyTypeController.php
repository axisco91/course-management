<?php

namespace App\Http\Controllers;

use App\Models\CompanyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company_types/list');
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
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $company_type = CompanyType::create([
            'type' => $request['type'],
        ]);

        return response()->json([
            'status' => 200,
            'company_type' => $company_type
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
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $company_type = CompanyType::find($request['id']);

        $company_type->update([
            'type' => $request['type'],
        ]);

        return response()->json([
            'status' => 200,
            'company_activity' => $company_type
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
        $company_type = CompanyType::find($request['id']);

        $company_type->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function restCompanyTypes(Request  $request){
        $types = CompanyType::all();
        $data = [];

        foreach ($types as $type){
            $info = [
                'type' => $type['type'],
                'accions' => '<a class="btn btn-success" id="updateType" data-type="'.$type['type'].'" data-id="'.$type['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger" id="deleteType" data-id="'.$type['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];

            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}

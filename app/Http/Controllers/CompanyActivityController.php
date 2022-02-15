<?php

namespace App\Http\Controllers;

use App\Models\CompanyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company_activities/list');
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
            'actividad' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $company_activity = CompanyActivity::create([
            'activity' => $request['actividad'],
        ]);

        return response()->json([
            'status' => 200,
            'company_activity' => $company_activity
        ]);
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
            'actividad' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $company_activity = CompanyActivity::find($request['id']);

        $company_activity->update([
            'activity' => $request['actividad'],
        ]);

        return response()->json([
            'status' => 200,
            'company_activity' => $company_activity
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
        $company_activity = CompanyActivity::find($request['id']);

        $company_activity->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function restCompaniesActivities(Request  $request){
        $activities = CompanyActivity::all();
        $data = [];

        foreach ($activities as $activity){
            $info = [
                'activity' => $activity['activity'],
                'accions' => '<a class="btn btn-success btn-sm" id="updateActivity" data-activity="'.$activity['activity'].'" data-id="'.$activity['id'].'"><i class="far fa-edit"></i></a> <a class="btn btn-danger btn-sm" id="deleteActivity" data-id="'.$activity['id'].'"><i class="far fa-trash-alt"></i></a>'
            ];

            array_push($data, $info);
        }
        return response()->json(['data' => $data]);
    }
}

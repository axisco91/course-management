<?php

namespace App\Http\Controllers\API;
use App\Models\IncidenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class IncidenceTypeController extends BaseController
{
    public function getIncidenceTypes() {
        try {
            return IncidenceType::getIncidenceTypes();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $type = IncidenceType::createIncidenceType($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence_type' => $type
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $type = IncidenceType::updateIncidenceType($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'incidence_type' => $type
        ]);
    }

    public function getIncidenceType($id){
        $type = IncidenceType::find($id);
        if ($type) {
            return response()->json([
                'status' => 200,
                'incidence_type' => $type
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Centro no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                IncidenceType::destroy($id);
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
}

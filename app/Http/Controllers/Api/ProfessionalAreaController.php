<?php

namespace App\Http\Controllers\API;
use App\Models\ProfessionalArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfessionalAreaController extends BaseController
{
    public function professionalAreas() {
        try {
            return ProfessionalArea::getProfessionalAreas();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $area = ProfessionalArea::createProfessionalArea($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_area' => $area
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $area = ProfessionalArea::updateProfessionalArea($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_area' => $area
        ]);
    }

    public function getProfessionalArea($id){
        $area = ProfessionalArea::find($id);
        if ($area) {
            return response()->json([
                'status' => 200,
                'professional_area' => $area
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Area no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProfessionalArea::destroy($id);
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

    public function count(){
        return ProfessionalArea::count();
    }
}

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
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $area = ProfessionalArea::createProfessionalArea($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_area' => ProfessionalArea::getProfessionalArea($area->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $area = ProfessionalArea::updateProfessionalArea($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_area' => ProfessionalArea::getProfessionalArea($area->id)
        ]);
    }

    public function getProfessionalArea($id){
        $area = ProfessionalArea::getProfessionalArea($id);
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(){
        return ProfessionalArea::count();
    }
}

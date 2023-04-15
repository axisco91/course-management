<?php

namespace App\Http\Controllers\API;
use App\Models\ProfessionalFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfessionalFamilyController extends BaseController
{
    public function professionalFamilies() {
        try {
            return ProfessionalFamily::getProfessionalFamilies();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $family = ProfessionalFamily::createProfessionalFamily($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_family' => ProfessionalFamily::getProfessionalFamily($family->id)
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $family = ProfessionalFamily::updateProfessionalFamily($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'professional_family' => ProfessionalFamily::getProfessionalFamily($family->id)
        ]);
    }

    public function getProfessionalFamily($id){
        $family = ProfessionalFamily::getProfessionalFamily($id);
        if ($family) {
            return response()->json([
                'status' => 200,
                'professional_family' => $family
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Familia no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                ProfessionalFamily::destroy($id);
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
        return ProfessionalFamily::count();
    }
}

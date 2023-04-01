<?php

namespace App\Http\Controllers\API;
use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends BaseController
{
    public function certifications() {
        try {
            return Certification::getCertifications();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e.message
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $certification = Certification::createCertification($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'certification' => $certification
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $certification = Certification::updateCertification($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'student' => $certification
        ]);
    }

    public function getCertification($id){
        $certification = Certification::find($id);
        if ($certification) {
            return response()->json([
                'status' => 200,
                'certification' => $certification
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Certificación no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                Certification::destroy($id);
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
        return Certification::count();
    }
}

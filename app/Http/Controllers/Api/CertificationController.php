<?php

namespace App\Http\Controllers\Api;
use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\Module;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;

class CertificationController extends BaseController
{
    public function certifications() {
        try {
            return Certification::getCertifications();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
            $certification = Certification::createCertification($request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'certification' => Certification::getCertification($certification->id)
        ]);
    }

    public function edit($id, Request $request){
        try {
            $certification = Certification::updateCertification($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'certification' => Certification::getCertification($certification->id)
        ]);
    }

    public function getCertification($id){
        $certification = Certification::getCertification($id);
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Volver a calcular horas de los certificados
     * @return void
     */
    public function recalculateHours(){
        $certifications = Certification::all();
        foreach($certifications as $certification) {
            $hours = 0;
            $face_to_face_hours = 0;
            $teletraining_hours = 0;
            $certification_elements = CertificationElement::where('certification_id', $certification->id)->get();
            foreach ($certification_elements as $certification_element) {
                if ($certification_element){
                    if ($certification_element->training_unit_id) {
                        $training_unit = TrainingUnit::find($certification_element->training_unit_id);
                        $face_to_face_hours = $face_to_face_hours + $training_unit['face_to_face_hours'];
                        $teletraining_hours = $teletraining_hours + $training_unit['teletraining_hours'];
                    } else if($certification_element->module_id) {
                        $module = Module::find($certification_element->module_id);
                        $face_to_face_hours = $face_to_face_hours + $module['face_to_Face_hours'];
                        $teletraining_hours = $teletraining_hours + $module['teletraining_hours'];
                    }
                }
            }
            $certification->update([
                'face_to_face_hours' => $face_to_face_hours,
                'teletraining_hours' => $teletraining_hours
            ]);
        }
    }
}

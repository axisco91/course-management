<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\Module;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificationController extends BaseController
{
    public function certifications(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            return Certification::getCertifications($mainCompanyId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $certification = Certification::createCertification($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'certification' => Certification::getCertification($certification->id, $mainCompanyId)
        ]);
    }

    public function edit($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $certification = Certification::updateCertification($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'certification' => Certification::getCertification($certification->id, $mainCompanyId)
        ]);
    }

    public function getCertification($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $certification = Certification::getCertification($id, $mainCompanyId);

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

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $certification = Certification::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$certification) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Certificado no existe'
                    ]);
                }

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
    public function recalculateHours(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $certifications = Certification::FilterMainCompany($mainCompanyId)->get();

        foreach($certifications as $certification) {
            $face_to_face_hours = 0;
            $teletraining_hours = 0;
            $certification_elements = CertificationElement::where('certification_id', $certification->id)
                ->FilterMainCompany($mainCompanyId)
                ->get();

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

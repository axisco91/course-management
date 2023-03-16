<?php

namespace App\Http\Controllers\API;
use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\Module;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function getElements($id) {
        return response()->json([
            'elements' => CertificationElement::getCertificationElement($id)
        ]);
    }

    public function getNotUsedModules($id) {
        return Module::getModulesNotInCertification($id);
    }

    public function getNotUsedUnits($id) {
        return TrainingUnit::getTrainingUnitsNotInCertification($id);
    }

    public function addElement($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $training_unit = CertificationElement::createCertificationElement($this->selected_id, $id, $type);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_unit_module' => TrainingUnitsModule::getTrainingUnitModule($training_unit->id),
            'module' => Module::find($training_unit->id)
        ]);
    }

    public function removeElement($id){
        try {
            $data = TrainingUnitsModule::deleteTrainingUnitModule($id);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function count(){
        return Certification::count();
    }
}

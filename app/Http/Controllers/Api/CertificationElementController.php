<?php

namespace App\Http\Controllers\API;
use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\Module;
use App\Models\TrainingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CertificationElementController extends BaseController
{

    public function getElements($id) {
        return response()->json([
            'elements' => CertificationElement::getCertificationElement($id)
        ]);
    }

    public function getModules($id) {
        return Module::getModulesNotInCertification($id);
    }

    public function getUnits($id) {
        return TrainingUnit::getTrainingUnitsNotInCertification($id);
    }

    public function create($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $element = CertificationElement::createCertificationElement($id, $data['id'], $data['type']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }
        $module = null;
        $unit = null;
        if ($element->module_id) {
            $module = Module::select('modules.*', 'modules.id as value', 'modules.name as label')
                ->where('id', $element->module_id)->first();
        }
        if ($element->training_unit_id) {
            $unit = TrainingUnit::select('training_units.*', 'training_units.id as value', 'training_units.name as label')
                ->where('id', $element->training_unit_id)->first();
        }
        $element = CertificationElement::select('certification_elements.*', 'training_units.name as training_unit_name',
            'training_units.exam_hours as training_unit_exam_hours', 'modules.exam_hours as module_exam_hours',
            'training_units.tutoring_hours as training_unit_tutoring_hours', 'modules.tutoring_hours as module_tutoring_hours',
            'training_units.face_to_face_hours as training_unit.face_to_face_hours', 'modules.face_to_face_hours as module_face_to_face_hours',
            'training_units.teletraining_hours as training_unit_teletraining_hours', 'modules.teletraining_hours as module_teletraining_hours',
            'training_units.total_hours as training_unit.total_hours', 'modules.total_hours as module_total_hours',
            'training_units.formative_unit', 'modules.name as module_name', 'modules.formative_module')
            ->leftjoin('training_units', 'training_units.id', '=', 'certification_elements.training_unit_id')
            ->leftjoin('modules', 'modules.id', '=', 'certification_elements.module_id')
            ->where('certification_elements.id', $element->id)->first();
        return response()->json([
            'status' => 200,
            'element' => $element,
            'module' => $module,
            'unit' => $unit
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                $element = CertificationElement::find($id);
                $module = null;
                $unit = null;
                if ($element->module_id) {
                    $module = Module::select('modules.*', 'modules.id as value', 'modules.name as label')
                        ->where('id', $element->module_id)->first();
                }
                if ($element->training_unit_id) {
                    $unit = TrainingUnit::select('training_units.*', 'training_units.id as value', 'training_units.name as label')
                        ->where('id', $element->training_unit_id)->first();
                }
                CertificationElement::deleteCertificationElement($id);
                CertificationElement::destroy($id);
                return response()->json([
                    'status' => 200,
                    'module' => $module,
                    'unit' => $unit
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

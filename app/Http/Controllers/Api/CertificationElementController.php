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
            'elements' => CertificationElement::getCertificationElements($id)
        ]);
    }

    public function getElement($id) {
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
        try {
            $element = CertificationElement::createCertificationElement($id, $request['id'], $request['type']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
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
        $element = CertificationElement::getCertificationElement($element->id);
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
                    $module = Module::getModule($module->id);
                }
                if ($element->training_unit_id) {
                    $unit = TrainingUnit::select('training_units.*', 'training_units.id as value', 'training_units.name as label')
                        ->where('id', $element->training_unit_id)->first();
                    $unit = TrainingUnit::getTrainingUnit($unit->id);
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
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}

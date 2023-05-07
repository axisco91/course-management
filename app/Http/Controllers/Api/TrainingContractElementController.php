<?php

namespace App\Http\Controllers\API;
use App\Models\Certification;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use Illuminate\Http\Request;

class TrainingContractElementController extends BaseController
{

    public function getElements($id) {
        if ($id) {
            try {
                return TrainingContractElement::getTrainingContractElements($id);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function create($id, Request $request){
        try {
            $element = TrainingContractElement::createTrainingContractElement($id, $request['id'], $request['type']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }
        $certification = null;
        $training_action = null;
        if ($element->certification_id) {
            $certification = Certification::select('certifications.*', 'certifications.id as value', 'certifications.name as label')
                ->where('id', $element->certification_id)->first();
        }
        if ($element->training_action_id) {
            $training_action = TrainingAction::select('training_actions.*', 'training_actions.id as value', 'training_actions.name as label')
            ->where('id', $element->training_action_id)->first();
        }
        $element = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->where('training_contract_elements.id', $element->id)->first();
        return response()->json([
            'status' => 200,
            'element' => $element,
            'certification' => $certification,
            'training_action' => $training_action
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                $element = TrainingContractElement::find($id);
                $certification = null;
                $training_action = null;
                if ($element->certification_id) {
                    $certification = Certification::select('certifications.*', 'certifications.id as value', 'certifications.name as label')
                        ->where('id', $element->certification_id)->first();
                }
                if ($element->training_action_id) {
                    $training_action = TrainingAction::select('training_actions.*', 'training_actions.id as value', 'training_actions.name as label')
                        ->where('id', $element->training_action_id)->first();
                }
                TrainingContractElement::deleteTrainingContractElement($id);
                TrainingContractElement::destroy($id);
                return response()->json([
                    'status' => 200,
                    'certification' => $certification,
                    'training_action' => $training_action
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

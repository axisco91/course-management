<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractElement extends Model
{
	use HasFactory;

    protected $fillable = ['certification_id', 'training_action_id', 'training_contract_id'];

    public static function getTrainingContractElements($training_contract_id){
        $training_contract_elements = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name',
            'training_actions.formative_action', 'training_actions.name as training_action_name')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->where('training_contract_id', $training_contract_id)->get();
        return $training_contract_elements;
    }

    public static function createTrainingContractElement($training_contract_id, $element_id, $type){
        $hours = 0;
        $training_contract_element = null;
        if ($type == 'certification_id'){
            $training_contract_element = TrainingContractElement::where('certification_id', $element_id)
                ->where('training_contract_id', $training_contract_id)->first();
            if (!$training_contract_element){
                $training_contract_element = TrainingContractElement::create([
                    'training_contract_id' => $training_contract_id,
                    'certification_id' => $element_id
                ]);
                $certification = Certification::find($element_id);
                $hours = $certification['total_hours'];
            }
        } else if ($type == 'specialty_id'){
            $training_contract_element = TrainingContractElement::where('training_action_id', $element_id)
                ->where('training_contract_id', $training_contract_id)->first();
            if (!$training_contract_element){
                $training_contract_element = TrainingContractElement::create([
                    'training_contract_id' => $training_contract_id,
                    'training_action_id' => $element_id
                ]);
                $training_action = TrainingAction::find($element_id);
                $hours = $training_action['total_hours'];
            }
        }

        $training_contract = TrainingContract::find($training_contract_id);
        $training_contract->update([
            'total_hours' => $training_contract['total_hours'] + $hours
        ]);
        return $training_contract_element;
    }

    public static function deleteTrainingContractElement($training_contract_id, $id){
        $training_contract = TrainingContract::find($training_contract_id);
        $training_contract_element = TrainingContractElement::find($id);
        $hours = 0;
        if ($training_contract){
            if ($training_contract_element){
                if ($training_contract_element->training_action_id) {
                    $training_action = TrainingAction::find($training_contract_element->training_action_id);
                    $hours = $training_action['total_hours'];
                } else if($training_contract_element->certification_id) {
                    $certification = Certification::find($training_contract_element->certification_id);
                    $hours = $certification['total_hours'];
                }
                $training_contract_element->delete();
            }
            $training_contract->update([
                'total_hours' => - $hours
            ]);
        }
    }
}

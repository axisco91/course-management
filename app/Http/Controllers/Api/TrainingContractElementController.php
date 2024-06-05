<?php

namespace App\Http\Controllers\Api;
use App\Models\Certification;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class TrainingContractElementController extends BaseController
{

    public function getAll(){
        try {
            $elements = TrainingContractElement::getAllTrainingContractElements();
            return response()->json([
                'status' => 200,
                'elements' => $elements
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getTrainingContractElements($id) {
        if ($id) {
            try {
                $elements = TrainingContractElement::info()->trainingContracts($id);
                $user = User::find(Auth::id());
                if ($user->teacher_id) {
                    $elements = $elements->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                        ->where('courses.teacher_id', $user->teacher_id);
                }
                $elements = $elements->get();
                $planned = 0;
                foreach ($elements as $element) {
                    if ($element->certification_total_hours) {
                        $planned = $planned + $element->training_action_total_hours;
                    } else if ($element->training_action_total_hours) {
                        $planned = $planned + $element->training_action_total_hours;
                    }
                }
                return response()->json([
                    'status' => 200,
                    'elements' => $elements,
                    'planned' => $planned,
                    'message' => ' element fetched successfully'

                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function store($id, Request $request){

        try {
            $element = TrainingContractElement::createTrainingContractElement($id, $request['id'], $request['type'], $request['beginning'], $request['end']);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
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
                $element = TrainingContractElement::getTrainingContractElement($id);
                $certification = null;
                $training_action = null;
                $formation_hours = 0;
                if ($element->certification_total_hours) {
                    $formation_hours = $element->training_action_total_hours;
                } else if ($element->training_action_total_hours) {
                    $formation_hours = $element->training_action_total_hours;
                }
                if ($element->certification_id) {
                    $certification = Certification::select('certifications.*', 'certifications.id as value', 'certifications.name as label')
                        ->where('id', $element->certification_id)
                        ->first();
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
                    'training_action' => $training_action,
                    'formation_hours' => $formation_hours,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function index(Request $request){
        try {
            $trainingContractElements = TrainingContractElement::getElements();
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $trainingContractElements = $trainingContractElements->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->student) {
                $trainingContractElements = $trainingContractElements->where('students.id', 'LIKE', $request->student);
            }
            if ($request->company) {
                $trainingContractElements = $trainingContractElements->where('companies.id', $request->company);
            }
            if ($request->beginning) {
                $trainingContractElements = $trainingContractElements->where('training_contract_elements.beginning', '>=', $request->beginning);
            }
            if ($request->end) {
                $trainingContractElements = $trainingContractElements->where('training_contract_elements.beginning', '<=', $request->end);
            }

            $trainingContractElements = $trainingContractElements->orderBy('order', 'asc')->get();
            return $trainingContractElements;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function orderTrainingContractElements(Request $request) {
        try {
            if ($request->elementListChange) {
                TrainingContractElement::orderTrainingContractElement($request->elementListChange);
                return response()->json([
                    'status' => 200,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id) {
        try {
            $element = TrainingContractElement::find($id);
            return response()->json([
                'status' => 200,
                'element' => $element
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function editDate($id, Request $request) {
        try {
            $element = TrainingContractElement::find($id);
            $element->update([
                'beginning' =>  $request['beginning'] ? Carbon::createFromFormat('d-m-Y', $request['beginning'])->format('Y-m-d') : null,
                'end' =>  $request['end'] ? Carbon::createFromFormat('d-m-Y', $request['end'])->format('Y-m-d') : null,
            ]);
            return response()->json([
                'status' => 200,
                'element' =>  TrainingContractElement::info()->where('training_contract_elements.id', $element->id)->first()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getAllTrainingContractElements() {
        try {
            $elements = TrainingContractElement::getAllTrainingContractElements();
            dd($elements); 
            return response()->json(['status' => 200, 'elements' => $elements]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'error' => 'Error fetching training contract elements']);
        }
    }

    public function editTutorInfo($id, Request $request) {
        try {
            $element = TrainingContractElement::find($id);
            if (!$element) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Training Contract Element not found'
                ]);
            }
    
            $element->update([
                'training_tutor' => $request->input('training_tutor'),
                'training_tutor_dni' => $request->input('training_tutor_dni')
            ]);
    
            return response()->json([
                'status' => 200,
                'element' => TrainingContractElement::info()->where('training_contract_elements.id', $element->id)->first(),
                'message' => 'Tutor information updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Certification;
use App\Models\TrainingAction;
use App\Models\TrainingContractElement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class TrainingContractElementController extends BaseController
{
    public function getAll(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $elements = TrainingContractElement::getAllTrainingContractElements($mainCompanyId);
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

    public function getActive(Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $elements = TrainingContractElement::getActiveTrainingContractElements($mainCompanyId);
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

    public function getTrainingContractElements($id, Request $request) {
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $elements = TrainingContractElement::info($mainCompanyId)->trainingContracts($id, $mainCompanyId);
                $user = User::find(Auth::id());
                if ($user->teacher_id) {
                    $elements = $elements->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                        ->where('courses.teacher_id', $user->teacher_id);
                }
                $elements = $elements->get();
                $planned = 0;
                foreach ($elements as $element) {
                    if ($element->certification_total_hours) {
                        $planned = $planned + $element->certification->total_hours;
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = [
                'training_contract_id' => $id,
                'element_id' => $request['id'],
                'type' => $request['element_type'],
                'main_company_id' => $mainCompanyId,
            ];
            $element = TrainingContractElement::createTrainingContractElement($data);

            $certification = null;
            $trainingAction = null;
            if ($element->certification_id) {
                $certification = Certification::select('certifications.*', 'certifications.id as value', 'certifications.name as label')
                    ->where('id', $element->certification_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
            }
            if ($element->training_action_id) {
                $trainingAction = TrainingAction::select('training_actions.*', 'training_actions.id as value', 'training_actions.name as label')
                    ->where('id', $element->training_action_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
            }
            $element = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
                'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours')
                ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
                ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
                ->where('training_contract_elements.id', $element->id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            return response()->json([
                'status' => 200,
                'element' => $element,
                'certification' => $certification,
                'training_action' => $trainingAction
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $element = TrainingContractElement::getTrainingContractElement($id, $mainCompanyId);
                $certification = null;
                $trainingAction = null;
                $formation_hours = 0;
                if ($element->certification_total_hours) {
                    $formation_hours = $element->training_action_total_hours;
                } else if ($element->training_action_total_hours) {
                    $formation_hours = $element->training_action_total_hours;
                }
                if ($element->certification_id) {
                    $certification = Certification::select('certifications.*', 'certifications.id as value', 'certifications.name as label')
                        ->where('id', $element->certification_id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();
                }
                if ($element->training_action_id) {
                    $trainingAction = TrainingAction::select('training_actions.*', 'training_actions.id as value', 'training_actions.name as label')
                        ->where('id', $element->training_action_id)
                        ->FilterMainCompany($mainCompanyId)
                        ->first();
                }

                TrainingContractElement::deleteTrainingContractElement($id, $mainCompanyId);
                TrainingContractElement::destroy($id);
                return response()->json([
                    'status' => 200,
                    'certification' => $certification,
                    'training_action' => $trainingAction,
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $trainingContractElements = TrainingContractElement::getElements($mainCompanyId);
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $trainingContractElements = $trainingContractElements->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                    ->where('courses.teacher_id', $user->teacher_id)
                    ->FilterMainCompany($mainCompanyId);
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            // Registrar el inicio del método y los datos recibidos
            Log::info('Entrando en orderTrainingContractElements', ['request' => $request->all()]);

            if ($request->elementListChange) {
                // Registrar los datos específicos de elementListChange
                Log::info('Datos de elementListChange recibidos', ['elementListChange' => $request->elementListChange]);

                $elementList = json_decode($request->elementListChange, true);
                TrainingContractElement::orderTrainingContractElement($elementList, $mainCompanyId);

                // Registrar que el ordenamiento se realizó correctamente
                Log::info('Ordenamiento realizado con éxito');

                return response()->json([
                    'status' => 200,
                ]);
            } else {
                // Registrar que no se recibió elementListChange
                Log::warning('No se recibió elementListChange en la solicitud');

                return response()->json([
                    'status' => 400,
                    'message' => 'No se recibió elementListChange'
                ]);
            }
        } catch (\Exception $e) {
            // Registrar el error con su mensaje
            Log::error('Error en orderTrainingContractElements', ['exception' => $e->getMessage()]);

            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }



    public function show($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $element = TrainingContractElement::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$element) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Elemento no encontrado'
                ]);
            }

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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $element = TrainingContractElement::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$element) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Elemento no encontrado'
                ]);
            }

            $data = $request->all();

            $element->updateDates($data);

            return response()->json([
                'status' => 200,
                'element' =>  TrainingContractElement::info($mainCompanyId)->where('training_contract_elements.id', $element->id)->first()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getAllTrainingContractElements(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $elements = TrainingContractElement::getAllTrainingContractElements($mainCompanyId);
            dd($elements);
            return response()->json(['status' => 200, 'elements' => $elements]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'error' => 'Error fetching training contract elements']);
        }
    }

    /**
     * Guardamos la info del tutor de la convocatoria
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTutorInfo($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $element = TrainingContractElement::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$element) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Elemento no encontrado'
                ]);
            }

            $data = [
                'training_tutor' => $request->input('training_tutor'),
                'training_tutor_dni' => $request->input('training_tutor_dni')
            ];

            $element->updateTutorInfo($element, $data);

            return response()->json([
                'status' => 200,
                'element' => TrainingContractElement::info($mainCompanyId)->where('training_contract_elements.id', $element->id)->first(),
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

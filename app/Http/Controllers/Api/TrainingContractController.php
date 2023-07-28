<?php

namespace App\Http\Controllers\Api;
use App\Models\Advisor;
use App\Models\Certification;
use App\Models\Chore;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Registration;
use App\Models\Tracing;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractFestival;
use App\Models\TrainingContractsExcludedDay;
use App\Models\User;
use App\Services\RegistrationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrainingContractController extends BaseController
{
    private $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Obtenemos todos los CFA
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainingContracts() {
        try {
            return TrainingContract::getTrainingContracts();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Creamos el CFA
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        try {
            $contract = TrainingContract::createTrainingContract($request);
            if ($request->has('clone_id')) {
                $trainingContractElements = TrainingContractElement::trainingContracts($request->clone_id)->get();
                foreach ($trainingContractElements as $trainingContractElement) {
                    TrainingContractElement::create([
                        'training_contract_id' => $contract->id,
                        'certification_id' => $trainingContractElement->certification_id,
                        'training_action_id' => $trainingContractElement->training_action_id
                    ]);
                }
            }
            $elements = TrainingContractElement::info()->trainingContracts($contract->id)->get();
            $planned = 0;
            foreach ($elements as $element) {
                if ($element->certification_total_hours) {
                    $planned = $planned + $element->training_action_total_hours;
                } else if ($element->training_action_total_hours) {
                    $planned = $planned + $element->training_action_total_hours;
                }
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract' => TrainingAction::getTrainingAction($contract->id),
            'training_contract_elements' =>$elements
        ]);
    }

    /**
     * Editamos el CFA
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request){
        try {
            $contract = TrainingContract::updateTrainingContract($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract' => TrainingContract::getTrainingContract($contract->id)
        ]);
    }

    /**
     * Obtenemos el CFA
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainingContract($id){
        $contract = TrainingContract::getTrainingContract($id);
        if ($contract) {
            return response()->json([
                'status' => 200,
                'training_contract' => $contract
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Contrato no existe'
        ]);
    }

    /**
     * Eliminamos el CFA
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id){
        if ($id) {
            try {
                TrainingContract::destroy($id);
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
     * Obtenemos el numbero CFA
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCFANumber(){
        $training = TrainingContract::orderBy('id', 'desc')->first();
        $id = $training['id']+1;
        if ($id < 10) {
            $number_cfa = '000'.$id;
        }
        else if ($id < 100) {
            $number_cfa = '00'.$id;
        }
        else if ($id < 1000) {
            $number_cfa = '0'.$id;
        } else {
            $number_cfa = $id;
        }
        return response()->json([
            'number_cfa' => $number_cfa
        ]);
    }

    /**
     * Obtenemos las especialidades
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getSpecialties($id) {
        if ($id) {
            try {
                return TrainingAction::getSpecialties($id);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Obtenemos los certificaciones
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getCertifications($id) {
        if ($id) {
            try {
                return Certification::getCertificationsNotinTrainingContract($id);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function countTrainingContracts() {
        return TrainingContract::count();
    }

    /**
     * Calcula las horas del curso y le añade fechas a los cursos
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateHours($id) {
        $record = TrainingContract::findOrFail($id);
        $training_contract_certifications = TrainingContractElement::getTrainingContractElements($id);
        $cont_days = 0;
        $date = Carbon::parse($record->beginning_formation);
        $end_date = Carbon::parse($record->end_formation);
        $hours_days = 0;
        $total_hours = 0;
        do {
            $excluded = TrainingContractsExcludedDay::nonWorkingDay($id, $date);
            if ($excluded != true){
                $excluded = TrainingContractFestival::existDay($date, $record->id)->first();
                if (!$excluded){
                    switch($date->dayOfWeek){
                        case 0:
                            if ($record->sunday == 1){
                                $cont_days++;
                            }
                            break;
                        case 1:
                            if ($record->monday == 1){
                                $cont_days++;
                            }
                            break;
                        case 2:
                            if ($record->tuesday == 1){
                                $cont_days++;
                            }
                            break;
                        case 3:
                            if ($record->wednesday == 1){
                                $cont_days++;
                            }
                            break;
                        case 4:
                            if ($record->thursday == 1){
                                $cont_days++;
                            }
                            break;
                        case 5:
                            if ($record->friday == 1){
                                $cont_days++;
                            }
                            break;
                        case 6:
                            if ($record->saturday == 1){
                                $cont_days++;
                            }
                            break;
                    }
                }
            }
            $date->addDay();
        } while($end_date->gt($date));
        if ($cont_days != 0){
            $hours_days = $record->total_hours / $cont_days;
            $hours_days = floor($hours_days * 100) / 100;
            $record->update([
                'total_days' => $cont_days,
                'daily_hours' => $hours_days
            ]);
            $total_days = $cont_days;
            $daily_hours = $hours_days;
        }
        foreach($training_contract_certifications as $training_element){
            if ($training_element === $training_contract_certifications[0]){
                $beginning = Carbon::parse($record->beginning_formation);
            }
            $training_element->update([
                'beginning' => $beginning
            ]);

              if ($training_element->training_action_id){
                $training_action = TrainingAction::find($training_element->training_action_id);
                $total_hours = $total_hours + $training_action->total_hours;
                $total_days = $training_action->total_hours / $hours_days;
            } else if($training_element->certification_id) {
                $certification = Certification::find($training_element->certification_id);
                $total_hours = $total_hours + $certification->total_hours;
                $total_days = $training_action->total_hours / $hours_days;
            } else {
                break;
            }
            $total_days = round($total_days);
            $training_element->update([
                'total_days' => $total_days
            ]);
            do {
                $excluded = TrainingContractsExcludedDay::nonWorkingDay($id, $beginning);
                if ($excluded != true){
                    $excluded = TrainingContractFestival::existDay($beginning, $record->id)->first();
                    if (!$excluded) {
                        switch ($beginning->dayOfWeek) {
                            case 0:
                                if ($record->sunday == 1) {
                                    $total_days--;
                                }
                                break;
                            case 1:
                                if ($record->monday == 1) {
                                    $total_days--;
                                }
                                break;
                            case 2:
                                if ($record->tuesday == 1) {
                                    $total_days--;
                                }
                                break;
                            case 3:
                                if ($record->wednesday == 1) {
                                    $total_days--;
                                }
                                break;
                            case 4:
                                if ($record->thursday == 1) {
                                    $total_days--;
                                }
                                break;
                            case 5:
                                if ($record->friday == 1) {
                                    $total_days--;
                                }
                                break;
                            case 6:
                                if ($record->saturday == 1) {
                                    $total_days--;
                                }
                                break;
                        }
                    }
                }
                $beginning = $beginning->addDay();
            } while($total_days > 0);
            $training_element->update([
                'end' => $beginning
            ]);
            $beginning = $beginning->addDay();
        }
        $record->update([
            'formation_hours' => $total_hours
        ]);

        $training_contract_certifications = TrainingContractElement::getTrainingContractElements($id);

        return response()->json([
            'status' => 200,
            'total_hours' => $total_hours,
            'daily_hours' => $daily_hours,
            'total_days' => $cont_days,
            'elements' => $training_contract_certifications
        ]);
    }

    /**
     * Crea el curso y matricula al alumno
     * @return void
     */
    public function register($id) {
        try {
            $training_contract_element = TrainingContractElement::where('id', $id)
                ->first();
            if ($training_contract_element && $training_contract_element->course_id == null) {
                $training_contract = TrainingContract::find($training_contract_element->training_contract_id);
                if ($training_contract) {
                    if ($training_contract_element->training_action_id) {
                        $training_action = TrainingAction::find($training_contract_element->training_action_id);

                    } else {
                        $certification = Certification::find($training_contract_element->certification_id);
                        if ($certification) {
                            if ($certification->training_action_id) {
                                $training_action = TrainingAction::find($certification->training_action_id);
                            } else {
                                $training_action = TrainingAction::where('name', $certification->name)
                                    ->first();
                                if ($training_action) {
                                    $certification->update([
                                        'training_action_id' => $training_action->id
                                    ]);
                                } else {
                                    $data = [
                                        'name' => $certification->name,
                                        'face_to_face_hours' => $certification->face_to_face_hours,
                                        'teletraining_hours' => $certification->teletraining_hours,
                                        'total_hours' => $certification->total_hours,
                                        'active' => 1,
                                        'specialty' => 0,
                                        'in_catalog' => 1
                                    ];
                                    $training_action = TrainingAction::create($data);
                                    $certification->update([
                                        'training_action_id' => $training_action->id
                                    ]);
                                }
                            }
                        }
                    }
                    if ($training_action) {
                        $course_data = Course::setName($training_action->id, null);
                        $course_type = CourseType::where('name', 'CFA')
                            ->first();
                        $courseData = [
                            'name' => $training_action->formative_action.' - '.$training_action->name,
                            'training_action_id' => $training_action->id,
                            'group' => $course_data['group'],
                            'teacher_id' => null,
                            'beginning' => Carbon::createFromFormat('Y-m-d', $training_contract_element->beginning)->format('d-m-Y'),
                            'end' => Carbon::createFromFormat('Y-m-d', $training_contract_element->end)->format('d-m-Y'),
                            'morning_schedule' => null,
                            'afternoon_schedule' => null,
                            'formation_center_id' => null,
                            'delivery_center_id' => null,
                            'course_observation' => null,
                            'welcome_date' => Carbon::createFromFormat('Y-m-d', $training_contract_element->beginning)->format('d-m-Y'),
                            'final_date' => Carbon::createFromFormat('Y-m-d', $training_contract_element->end)->format('d-m-Y'),
                            'price' => 0,
                            'nebrija' => 0,
                            'monday' => 0,
                            'tuesday' =>0,
                            'wednesday' => 0,
                            'thursday' => 0,
                            'friday' => 0,
                            'saturday' => 0,
                            'sunday' => 0,
                            'outsourced' => 0,
                            'reactivated' => 0,
                            'canceled' => 0,
                            'course_type_id' => $course_type->id
                        ];
                        $course = Course::createCourse($courseData);
                        $advisor_id = null;
                        $collaborator_id = null;
                        $company = Company::find($training_contract->company_id);
                        $advisor = Advisor::find($company->advisor_id);
                        $advisor_percentage = null;
                        $collaborator_percentage = null;
                        if ($advisor) {
                            if ($advisor['collaborator_id']){
                                $collaborator_id = $advisor['collaborator_id'];
                            }
                            if ($advisor['commission']){
                                $advisor_percentage = intval($advisor['commission']);
                            }
                        }
                        if ($company){
                            if ($company['advisor_id']){
                                $advisor_id = $company['advisor_id'];
                            }
                            if ($company['collaborator_id']){
                                $collaborator_id = $company['collaborator_id'];
                            }
                        }
                        if ($collaborator_id){
                            $user = User::find($collaborator_id);
                            if ($user){
                                $collaborator_percentage = $user['commission'];
                            }
                        }
                        $data = [
                            'course_id' => $course->id,
                            'company_id' => $training_contract->company_id,
                            'student_id' => $training_contract->student_id,
                            'advisor_id' => $advisor_id,
                            'collaborator_id' => $collaborator_id,
                            'price' => 0,
                            'profitability_id' => null,
                            'is_bonus' => 0
                        ];
                        $this->registrationService->create($data);
                        $training_contract_element->update([
                            'course_id' => $course->id
                        ]);
                    }
                }
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'element' => TrainingContractElement::find($id)
        ]);
    }

    public function trainingContractCSV(Request $request){
        try {
            if ($request) {
                return TrainingContract::getTrainingContractsCSV($request['company'], $request['student_id'], $request['status']);
            }
            return TrainingContract::getTrainingContractsCSV();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}

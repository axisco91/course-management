<?php

namespace App\Http\Controllers\Api;
use App\Models\Advisor;
use App\Models\Certification;
use App\Models\Chore;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\ExamTutorial;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request) {
        try {
            $trainingContracts = TrainingContract::getTrainingContracts();
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $trainingContracts = $trainingContracts->leftjoin('training_contract_elements', 'training_contract_elements.training_contract_id', '=', 'training_contracts.id')
                    ->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->company) {
                $trainingContracts = $trainingContracts->where('companies.name', $request->company);
            }
            if ($request->student_id) {
                $trainingContracts = $trainingContracts->where('training_contracts.student_id', $request->student_id);
            }
            if ($request->status) {
                $trainingContracts = $trainingContracts->where('training_contract_statuses.name', $request->status);
            }

            $trainingContracts = $trainingContracts->groupBy('training_contracts.id', 'training_contracts.number_cfa')->orderby('training_contracts.beginning', 'desc')->get();

            return $trainingContracts;
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
    public function store(Request $request){
        try {
            $contract = TrainingContract::createTrainingContract($request);
            if ($request->has('clone_id')) {
                $trainingContractElements = TrainingContractElement::trainingContracts($request->clone_id)->get();
                foreach ($trainingContractElements as $trainingContractElement) {
                    TrainingContractElement::create([
                        'training_contract_id' => $contract->id,
                        'certification_id' => $trainingContractElement->certification_id,
                        'training_action_id' => $trainingContractElement->training_action_id,
                        'total_days' => $trainingContractElement->total_days,
                        'beginning' => $trainingContractElement->beginning,
                        'end' => $trainingContractElement->end,
                        'order' => $trainingContractElement->order,
                        'course_id' => $trainingContractElement->course_id
                    ]);
                }
                $exams_tutorials = ExamTutorial::where('training_contract_id', $request->clone_id)
                    ->get();
                foreach($exams_tutorials as $exams_tutorial) {
                    $dataExam = [
                        'training_contract_id' => $contract->id,
                        'center_id' => $exams_tutorial->center_id,
                        'type' => $exams_tutorial->type,
                        'date' => $exams_tutorial->date,
                        'beginning' => $exams_tutorial->beginning,
                        'end' => $exams_tutorial->end
                    ];
                    ExamTutorial::createExamsTutorial($dataExam);
                }
                $festivals = TrainingContractFestival::where('training_contract_id', $request->clone_id)->Get();
                foreach($festivals as $festival) {
                    TrainingContractFestival::create([
                        'training_contract_id' => $contract->id,
                        'nacional_festival_id' => $festival->nacional_festival_id,
                        'province_festival_id' => $festival->province_festival_id,
                        'population_festival_id' => $festival->population_festival_id
                    ]);
                }
                $excluded_days = TrainingContractsExcludedDay::where('training_contract_id', $request->clone_id)->Get();
                foreach($excluded_days as $excluded_day) {
                    TrainingContractsExcludedDay::create([
                        'training_contract_id' => $contract->id,
                        'excluded_day_type_id' => $excluded_day->excluded_day_type_id,
                        'day' => $excluded_day->day,
                        'description' => $excluded_day->description,
                        'group' => $excluded_day->group
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
            'training_contract' => TrainingContract::getTrainingContracts()
                ->where('training_contracts.id', $contract->id)
                ->first(),
            'training_contract_elements' =>$elements
        ]);
    }

    /**
     * Editamos el CFA
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        try {
            TrainingContract::updateTrainingContract($id, $request);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract' => TrainingContract::getTrainingContracts()
                ->where('training_contracts.id', $id)
                ->first()
        ]);
    }

    /**
     * Obtenemos el CFA
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $contract = TrainingContract::getTrainingContracts()
            ->selectSub(function ($query) {
                $query->from('training_contract_bonuses')
                    ->selectRaw('SUM(amount)')
                    ->whereColumn('training_contract_bonuses.training_contract_id', 'training_contracts.id');
            }, 'total_amount')
            ->leftJoin('training_contract_bonuses', 'training_contract_bonuses.training_contract_id', '=', 'training_contracts.id')
            ->where('training_contracts.id', $id)
            ->first();
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
        $total = 0;
        $vacations = 0;
        $banckholiday= 0;
        $fin_semana = 0;
        do {
            $excluded = TrainingContractsExcludedDay::nonWorkingDay($id, $date);
            if ($excluded != true){
                $excluded = TrainingContractFestival::existDay($date, $record->id)->first();
                if (!$excluded){
                    switch($date->dayOfWeek){
                        case 0:
                            if ($record->sunday === 0){
                                $fin_semana++;
                            }
                            break;
                        case 1:
                            if ($record->monday === 1){
                                $cont_days++;
                            }
                            break;
                        case 2:
                            if ($record->tuesday === 1){
                                $cont_days++;
                            }
                            break;
                        case 3:
                            if ($record->wednesday === 1){
                                $cont_days++;
                            }
                            break;
                        case 4:
                            if ($record->thursday === 1){
                                $cont_days++;
                            }
                            break;
                        case 5:
                            if ($record->friday === 1){
                                $cont_days++;
                            }
                            break;
                        case 6:
                            if ($record->saturday === 0){
                                $fin_semana++;
                            }
                            break;
                    }
                } else {
                    $banckholiday++;
                }
            } else {
                $vacations++;
            }
            $total++;
            $date->addDay();
        } while($end_date->gte($date));
        if ($cont_days != 0){
            $hours_days = $record->formation_hours / $cont_days;
            $hours_days = round($hours_days, 2);
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
                'beginning' => $beginning->toDateString()
            ]);

              if ($training_element->training_action_id){
                $training_action = TrainingAction::find($training_element->training_action_id);
                $total_hours = $total_hours + $training_action->total_hours;
                $total_days = ($training_action->total_hours != 0 && $hours_days != 0) ? ($training_action->total_hours / $hours_days) : 0;
            } else if($training_element->certification_id) {
                $certification = Certification::find($training_element->certification_id);
                $total_hours = $total_hours + $certification->total_hours;
                $total_days = ($training_action->total_hours != 0 && $hours_days != 0) ? ($training_action->total_hours / $hours_days) : 0;
            } else {
                break;
            }
       //     $total_days = round($total_days, 2);
            $total_days = round($total_days);
            $training_element->update([
                'total_days' => $total_days
            ]);
            $cont = 0;
            while ($cont < $total_days) {
                $excluded = TrainingContractsExcludedDay::nonWorkingDay($id, $beginning);
                if ($excluded != true){
                    $excluded = TrainingContractFestival::existDay($beginning, $record->id)->first();
                    if (!$excluded) {
                        switch ($beginning->dayOfWeek) {
                            case 0:
                                if ($record->sunday == 1) {
                                    $cont++;
                                }
                                break;
                            case 1:
                                if ($record->monday == 1) {
                                    $cont++;
                                }
                                break;
                            case 2:
                                if ($record->tuesday == 1) {
                                    $cont++;
                                }
                                break;
                            case 3:
                                if ($record->wednesday == 1) {
                                    $cont++;
                                }
                                break;
                            case 4:
                                if ($record->thursday == 1) {
                                    $cont++;
                                }
                                break;
                            case 5:
                                if ($record->friday == 1) {
                                    $cont++;
                                }
                                break;
                            case 6:
                                if ($record->saturday == 1) {
                                    $cont++;
                                }
                                break;
                        }
                    }
                }
                if ($cont < $total_days) {
                    $beginning = $beginning->addDay();
                }
            }
            $training_element->update([
                'end' => $beginning->toDateString()
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

        $element = TrainingContractElement::select('training_contract_elements.*', 'certifications.name as certification_name', 'certifications.total_hours as certification_total_hours',
            'training_actions.formative_action', 'training_actions.name as training_action_name', 'training_actions.total_hours as training_action_total_hours')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftjoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->where('training_contract_elements.id', $id)->first();

        return response()->json([
            'status' => 200,
            'element' => $element
        ]);
    }
}

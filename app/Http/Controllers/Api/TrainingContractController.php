<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Advisor;
use App\Models\Certification;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\ExamTutorial;
use App\Models\Registration;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractFestival;
use App\Models\TrainingContractsExcludedDay;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class TrainingContractController extends BaseController
{
    /**
     * Obtenemos todos los CFA
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $trainingContracts = TrainingContract::getTrainingContracts($mainCompanyId);
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

            if (isset($request->not_canceled)) {
                $trainingContracts = $trainingContracts->whereNotIn('training_contracts.training_contract_status_id', [4,5, 6]);
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
            DB::beginTransaction();
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $contract = TrainingContract::createWithService($data);

            if ($request->has('clone_id')) {
                $trainingContractElements = TrainingContractElement::trainingContracts($request->clone_id, $mainCompanyId)->get();
                foreach ($trainingContractElements as $trainingContractElement) {
                    $data = [
                        'training_contract_id' => $contract->id,
                        'certification_id' => $trainingContractElement->certification_id,
                        'training_action_id' => $trainingContractElement->training_action_id,
                        'total_days' => $trainingContractElement->total_days,
                        'beginning' => null,
                        'end' => null,
                        'order' => $trainingContractElement->order,
                        'course_id' => null,
                        'main_company_id' => $mainCompanyId,
                    ];
                    TrainingContractElement::createWithService($data);
                }
                $exams_tutorials = ExamTutorial::where('training_contract_id', $request->clone_id)
                    ->FilterMainCompany($mainCompanyId)
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
                    ExamTutorial::createWithService($dataExam);
                }
                $festivals = TrainingContractFestival::where('training_contract_id', $request->clone_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->Get();
                foreach($festivals as $festival) {
                    TrainingContractFestival::create([
                        'training_contract_id' => $contract->id,
                        'nacional_festival_id' => $festival->nacional_festival_id,
                        'province_festival_id' => $festival->province_festival_id,
                        'population_festival_id' => $festival->population_festival_id,
                        'main_company_id' => $mainCompanyId,
                    ]);
                }
                $excluded_days = TrainingContractsExcludedDay::where('training_contract_id', $request->clone_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->Get();
                foreach($excluded_days as $excluded_day) {
                    TrainingContractsExcludedDay::create([
                        'training_contract_id' => $contract->id,
                        'excluded_day_type_id' => $excluded_day->excluded_day_type_id,
                        'day' => $excluded_day->day,
                        'description' => $excluded_day->description,
                        'group' => $excluded_day->group,
                        'main_company_id' => $mainCompanyId,
                    ]);
                }
            }
            $elements = TrainingContractElement::info($mainCompanyId)->trainingContracts($contract->id, $mainCompanyId)->get();
            $planned = 0;
            foreach ($elements as $element) {
                if ($element->certification_total_hours) {
                    $planned = $planned + $element->training_action_total_hours;
                } else if ($element->training_action_total_hours) {
                    $planned = $planned + $element->training_action_total_hours;
                }
            }
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        DB::commit();
        return response()->json([
            'status' => 200,
            'training_contract' => TrainingContract::getTrainingContracts($mainCompanyId)
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
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $trainingContract = TrainingContract::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$trainingContract) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Contrato no encontrado'
                ]);
            }

            $trainingContract->updateWithService($request->all());
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract' => TrainingContract::getTrainingContracts($mainCompanyId)
                ->where('training_contracts.id', $id)
                ->first()
        ]);
    }

    /**
     * Obtenemos el CFA
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $contract = TrainingContract::getTrainingContracts($mainCompanyId)
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
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $trainingContract = TrainingContract::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if (!$trainingContract) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Contrato no encontrado'
                    ]);
                }

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
    public function getCFANumber(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $training = TrainingContract::orderBy('id', 'desc')
            ->FilterMainCompany($mainCompanyId)
            ->first();
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
    public function getSpecialties($id, Request $request) {
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                return TrainingAction::getSpecialties($id, $mainCompanyId);
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
    public function getCertifications($id, Request $request) {
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                return Certification::getCertificationsNotinTrainingContract($id, $mainCompanyId);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function countTrainingContracts(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        return TrainingContract::FilterMainCompany($mainCompanyId)->count();
    }

    /**
     * Calcula las horas del curso y le añade fechas a los cursos
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateHours($id, Request $request)
    {
        try {
            DB::beginTransaction();
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            // Buscamos el contrato de formación por su ID; si no se encuentra, lanza una excepción
            $record = TrainingContract::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$record) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Contrato no encontrado'
                ]);
            }

            // Calculamos las horas formativas usando un método del modelo
            $hoursData = $record->calculateHours();

            // Obtenemos el usuario autenticado
            $user = User::find(Auth::id());

            // Si el usuario tiene un asesor asignado, aplicamos un filtro por asesor
            if ($user->advisor_id) {
                $record->where('advisor_id', $user->advisor_id);
            }

            // Devolvemos una respuesta HTTP con todos los datos calculados
            DB::commit();
            return response()->json([
                'status' => 200,
                'total_hours' => $hoursData['formative_hours_first_year'] + $hoursData['formative_hours_second_year'], // Total de horas de ambos años
                'daily_hours_1' => $hoursData['daily_hours_1'], // Horas diarias del primer año
                'daily_hours_2' => $hoursData['daily_hours_2'], // Horas diarias del segundo año
                'formative_hours_first_year' => $hoursData['formative_hours_first_year'], // Horas del primer año
                'formative_hours_second_year' => $hoursData['formative_hours_second_year'], // Horas del segundo año
                'total_days' => $hoursData['cont_days_first_year'] + $hoursData['cont_days_second_year'], // Total de días de formación
                'updated_elements' => $hoursData['updated_elements'], // Elementos actualizados en el proceso
                'end_formation' => $record->end_formation, // Fecha de fin de formación
                'end' => $record->end // Fecha final del contrato
            ]);
        } catch (\Exception $e) {
            // En caso de error, devolvemos una respuesta con el mensaje de la excepción
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Crea el curso y matricula al alumno
     * @return void
     */
    public function register($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $trainingContractElement = TrainingContractElement::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$trainingContractElement) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Elemento no encontrado'
                ]);
            }

            $trainingAction = null;
            if ($trainingContractElement && $trainingContractElement->course_id == null) {
                $trainingContract = TrainingContract::find($trainingContractElement->training_contract_id);
                if ($trainingContract) {
                    if ($trainingContractElement->training_action_id) {
                        $trainingAction = TrainingAction::find($trainingContractElement->training_action_id);

                    } else {
                        $certification = Certification::find($trainingContractElement->certification_id);
                        if ($certification) {
                            if ($certification->training_action_id) {
                                $trainingAction = TrainingAction::find($certification->training_action_id);
                            } else {
                                $trainingAction = TrainingAction::where('name', $certification->name)
                                    ->first();
                                if ($trainingAction) {
                                    $certification->update([
                                        'training_action_id' => $trainingAction->id
                                    ]);
                                } else {
                                    $data = [
                                        'name' => $certification->name,
                                        'face_to_face_hours' => $certification->face_to_face_hours,
                                        'teletraining_hours' => $certification->teletraining_hours,
                                        'total_hours' => $certification->total_hours,
                                        'active' => 1,
                                        'specialty' => 0,
                                        'in_catalog' => 1,
                                        'main_company_id' => $mainCompanyId
                                    ];
                                    $trainingAction = TrainingAction::create($data);
                                    $certification->update([
                                        'training_action_id' => $trainingAction->id
                                    ]);
                                }
                            }
                        }
                    }
                    if ($trainingAction) {
                        $course_data = Course::setName(null, $trainingAction->id, $mainCompanyId);
                        $course_type = CourseType::where('name', 'CFA')
                            ->first();
                        $courseData = [
                            'name' => $trainingAction->formative_action.' - '.$trainingAction->name,
                            'training_action_id' => $trainingAction->id,
                            'group' => $course_data['group'],
                            'teacher_id' => null,
                            'beginning' => Carbon::createFromFormat('Y-m-d', $trainingContractElement->beginning)->format('d-m-Y'),
                            'end' => Carbon::createFromFormat('Y-m-d', $trainingContractElement->end)->format('d-m-Y'),
                            'morning_schedule' => null,
                            'afternoon_schedule' => null,
                            'formation_center_id' => null,
                            'delivery_center_id' => null,
                            'course_observation' => null,
                            'welcome_date' => Carbon::createFromFormat('Y-m-d', $trainingContractElement->beginning)->format('d-m-Y'),
                            'final_date' => Carbon::createFromFormat('Y-m-d', $trainingContractElement->end)->format('d-m-Y'),
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
                            'course_type_id' => $course_type->id,
                            'main_company_id' => $mainCompanyId
                        ];
                        $course = Course::createWithService($courseData);
                        $advisor_id = null;
                        $collaborator_id = null;
                        $company = Company::find($trainingContract->company_id);
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
                            'company_id' => $trainingContract->company_id,
                            'student_id' => $trainingContract->student_id,
                            'advisor_id' => $advisor_id,
                            'collaborator_id' => $collaborator_id,
                            'price' => 0,
                            'profitability_id' => null,
                            'is_bonus' => 0,
                            'main_company_id' => $mainCompanyId,
                        ];
                        Registration::createWithService($data);
                        $data = [
                            'course_id' => $course->id,
                            'main_company_id' => $mainCompanyId,
                        ];
                        $trainingContractElement->addCourse($data);
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
            ->where('training_contract_elements.id', $id)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        return response()->json([
            'status' => 200,
            'element' => $element
        ]);
    }
    /**
     * Calcula las fechas de finalización del contrato y de la formación
     * @param $trainingContractId
     * @param $daily_hours_1
     * @param $daily_hours_2
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateEndDates($trainingContractId, $daily_hours_1, $daily_hours_2, Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $record = TrainingContract::where('id', $trainingContractId)
            ->FilterMainCompany($mainCompanyId)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => 404,
                'message' => 'Contrato no encontrado'
            ]);
        }

        Log::info("Calculating end dates for Training Contract ID: {$trainingContractId}");

        $h1 = (int) ($record->formative_hours_first_year ?? 0);
        $h2 = (int) ($record->formative_hours_second_year ?? 0);
        $d1 = (float) $daily_hours_1;
        $d2 = (float) $daily_hours_2;

        // Días necesarios (redondeo hacia arriba por tramo)
        $totalDaysNeeded = 0;
        if ($h1 > 0 && $d1 > 0) {
            $totalDaysNeeded += (int) ceil($h1 / $d1);
        }
        if ($h2 > 0 && $d2 > 0) {
            $totalDaysNeeded += (int) ceil($h2 / $d2);
        }

        Log::info("Total days needed: {$totalDaysNeeded}");

        // Si no hay días que computar, la fecha de fin es la de inicio
        $date = Carbon::parse($record->beginning_formation)->startOfDay();

        if ($totalDaysNeeded > 0) {
            $counted = 0;

            // Evaluamos el día actual ANTES de avanzar (incluye el día de inicio si procede)
            while ($counted < $totalDaysNeeded) {

                $isExcluded = TrainingContractsExcludedDay::nonWorkingDay(
                    $trainingContractId,
                    $date->toDateString(),
                    $mainCompanyId
                );

                // Asegúrate de que existDay devuelve booleano (ajusta si devuelve modelo/builder)
                $isFestival = TrainingContractFestival::existDay(
                    $date,
                    $record->id,
                    $mainCompanyId
                )->first();

                if (
                    !$date->isWeekend() &&
                    !$isExcluded &&
                    !$isFestival
                ) {
                    $counted++;
                    Log::info("Counted day: {$date->toDateString()}, total counted days: {$counted}");
                    if ($counted >= $totalDaysNeeded) {
                        break; // evita avanzar un día extra
                    }
                } else {
                    Log::info("Skipped day: {$date->toDateString()}");
                }

                $date->addDay(); // avanzamos al siguiente día civil
            }
        }

        $record->update(['end_formation' => $date]);

        Log::info("Final End Formation Date: " . $date->toDateString());

        return response()->json([
            'status' => 200,
            'end_formation' => $date->toDateString(),
        ]);
    }

    /**
     * Obtiene las horas de formación mensuales
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyFormationHours($id, Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
        $trainingContract = new TrainingContract();
        return $trainingContract->calculateMonthlyFormationHours($id, $mainCompanyId);
    }

    public function updateAdditionalClause($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $trainingContract = TrainingContract::where('id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if ($trainingContract) {
                $data = $request->all();
                $trainingContract->updateDocumentClause($data);
                return response()->json([
                    'status' => 200,
                    'training_contract' => TrainingContract::getTrainingContracts($mainCompanyId)
                        ->where('training_contracts.id', $trainingContract->id)
                        ->first()
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getTrainingContractActions($id, Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $actions = TrainingContractElement::where('training_contract_id', $id)
                ->whereNotNull('training_action_id')
                ->FilterMainCompany($mainCompanyId)
                ->get()
                ->pluck('training_action_id')
                ->toArray();

            $trainingActions = TrainingAction::select('*', DB::raw("CONCAT(training_actions.name) as label"))
                ->whereIn('training_actions.id', $actions)
                ->FilterMainCompany($mainCompanyId)
                ->get();

            return $trainingActions;

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}


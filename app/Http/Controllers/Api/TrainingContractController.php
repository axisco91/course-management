<?php

namespace App\Http\Controllers\Api;
use App\Exports\TrainingContractsExport;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\TrainingContractResource;
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
use Maatwebsite\Excel\Facades\Excel;


class TrainingContractController extends BaseController
{
    /**
     * Obtenemos todos los CFA
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $query = TrainingContract::getTrainingContracts($mainCompanyId);
            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                $query = $query->leftjoin('training_contract_elements', 'training_contract_elements.training_contract_id', '=', 'training_contracts.id')
                    ->leftjoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->company) {
                $query = $query->where('training_contracts.company_id', $request->company);
            }
            if ($request->student) {
                $query = $query->where('training_contracts.student_id', $request->student);
            }
            if ($request->status) {
                $query = $query->where('training_contract_status_id', $request->status);
            }

            if (isset($request->not_canceled)) {
                $query = $query->whereNotIn('training_contracts.training_contract_status_id', [4,5, 6]);
            }

            $sortParam = (string) $request->get('sort', '-start_date');
            $direction = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
            $sortField = ltrim($sortParam, '-');

            $sortMap = [
                'number' => 'training_contracts.number_cfa',
                'number_cfa' => 'training_contracts.number_cfa',
                'company' => 'companies.name',
                'company_name' => 'companies.name',
                'student_name' => 'students.name',
                'status' => 'training_contract_statuses.name',
                'training_contract_status_id' => 'training_contracts.training_contract_status_id',
                'provider' => 'providers.name',
                'provider_name' => 'providers.name',
                'start_date' => 'training_contracts.beginning',
                'beginning' => 'training_contracts.beginning',
                'end_date' => 'training_contracts.end',
                'end' => 'training_contracts.end',
            ];

            // Join only when the sort field belongs to a related table.
            if (in_array($sortField, ['company', 'company_name'])) {
                $query->leftJoin('companies', 'companies.id', '=', 'training_contracts.company_id');
            }
            if ($sortField === 'student_name') {
                $query->leftJoin('students', 'students.id', '=', 'training_contracts.student_id');
            }
            if (in_array($sortField, ['provider', 'provider_name'])) {
                $query->leftJoin('providers', 'providers.id', '=', 'training_contracts.provider_id');
            }
            if ($sortField === 'status') {
                $query->leftJoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id');
            }

            $sortColumn = $sortMap[$sortField] ?? 'training_contracts.beginning';

            $query = $query
                ->groupBy('training_contracts.id', 'training_contracts.number_cfa')
                ->orderBy($sortColumn, $direction);

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                // Resource sobre el paginator
                $trainingContracts = TrainingContractResource::collection($paginator);
                // Si no tienes Resource, podrías usar directamente:
                // $certifications = $paginator->items();

                // Datos de paginación (usar SIEMPRE el paginator, NO el builder)
                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'training_contracts' => $trainingContracts,
                        'links'          => $paginationData['links'],
                        'meta'           => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // SIN PAGINACIÓN
            $trainingContracts = TrainingContractResource::collection($query->get());
            // o, sin resource: $certifications = $query->get();

            return $this->sendResponse(
                [
                    'training_contracts' => $trainingContracts,
                ],
                trans('Obtenido con éxito')
            );
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
        $lockName = null;
        $lockAcquired = false;

        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;

            $lockName = $this->buildTrainingContractCreateLockName($data);
            $lockAcquired = $this->acquireDatabaseLock($lockName);

            if (!$lockAcquired) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Ya hay una creación de contrato en curso para estos datos. Inténtalo de nuevo en unos segundos.'
                ], 409);
            }

            DB::beginTransaction();

            $contract = TrainingContract::createWithService($data);

            if ($request->has('clone_id') && $contract->wasRecentlyCreated) {
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
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            if ($lockAcquired) {
                $this->releaseDatabaseLock($lockName);
            }

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        DB::commit();

        if ($lockAcquired) {
            $this->releaseDatabaseLock($lockName);
        }

        return $this->sendResponse(
            [
                'training_contract' => TrainingContract::getTrainingContracts($mainCompanyId)
                    ->where('training_contracts.id', $contract->id)
                    ->first(),
                'training_contract_elements' =>$elements
            ],
            trans('Creado con éxito')
        );
    }

    private function buildTrainingContractCreateLockName(array $data): string
    {
        $parts = [
            $data['main_company_id'] ?? '',
            $data['company_id'] ?? '',
            $data['student_id'] ?? '',
            $data['beginning'] ?? '',
            $data['end'] ?? '',
            $data['beginning_formation'] ?? '',
            $data['end_formation'] ?? '',
        ];

        return 'training_contract_create_' . sha1(implode('|', $parts));
    }

    private function acquireDatabaseLock(string $lockName): bool
    {
        $result = DB::selectOne('SELECT GET_LOCK(?, 10) AS acquired', [$lockName]);

        return (int) ($result->acquired ?? 0) === 1;
    }

    private function releaseDatabaseLock(?string $lockName): void
    {
        if (!$lockName) {
            return;
        }

        DB::selectOne('SELECT RELEASE_LOCK(?) AS released', [$lockName]);
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

        return $this->sendResponse(
            [
                'training_contract' => TrainingContract::getTrainingContracts($mainCompanyId)
                    ->where('training_contracts.id', $id)
                    ->first(),
            ],
            trans('Obtenido con éxito')
        );
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
            return $this->sendResponse(
                [
                    'training_contract' => $contract,
                ],
                trans('Obtenido con éxito')
            );
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
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
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

        return $this->sendResponse(
            [
                'number_cfa' => $number_cfa,
            ],
            trans('Obtenido con éxito')
        );
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

                return $this->sendResponse(
                    [
                        'training_action_specialties' => TrainingAction::getSpecialties($id, $mainCompanyId)->get(),
                    ],
                    trans('Obtenido con éxito')
                );
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

               $certifications = Certification::NotInTrainingContract($id, $mainCompanyId)->get();

                return $this->sendResponse(
                    [
                        'certifications' => $certifications,
                    ],
                    trans('Obtenido con éxito')
                );
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

            return $this->sendResponse(
                [
                    'total_hours' => $hoursData['formative_hours_first_year'] + $hoursData['formative_hours_second_year'], // Total de horas de ambos años
                    'daily_hours_1' => $hoursData['daily_hours_1'], // Horas diarias del primer año
                    'daily_hours_2' => $hoursData['daily_hours_2'], // Horas diarias del segundo año
                    'formative_hours_first_year' => $hoursData['formative_hours_first_year'], // Horas del primer año
                    'formative_hours_second_year' => $hoursData['formative_hours_second_year'], // Horas del segundo año
                    'total_days' => $hoursData['cont_days_first_year'] + $hoursData['cont_days_second_year'], // Total de días de formación
                    'updated_elements' => $hoursData['updated_elements'], // Elementos actualizados en el proceso
                    'end_formation' => $record->end_formation, // Fecha de fin de formación
                    'end' => $record->end // Fecha final del contrato
                ],
                trans('Obtenido con éxito')
            );
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

        return $this->sendResponse(
            [
                'element' => $element,
            ],
            trans('Obtenido con éxito')
        );
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

        return $this->sendResponse(
            [
                'end_formation' => $date->toDateString(),
            ],
            trans('Obtenido con éxito')
        );
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

                return $this->sendResponse(
                    [
                        'training_contract' => TrainingContract::getTrainingContracts($mainCompanyId)
                            ->where('training_contracts.id', $trainingContract->id)
                            ->first(),
                    ],
                    trans('Obtenido con éxito')
                );
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

            return $this->sendResponse(
                [
                    'training_contract_actions' => $trainingActions,
                ],
                trans('Obtenido con éxito')
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = TrainingContract::getTrainingContracts($mainCompanyId);

            $user = User::find(Auth::id());
            if ($user && $user->teacher_id) {
                $query->leftJoin('training_contract_elements', 'training_contract_elements.training_contract_id', '=', 'training_contracts.id')
                    ->leftJoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                    ->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->company) {
                $query->where('training_contracts.company_id', $request->company);
            }
            if ($request->student) {
                $query->where('training_contracts.student_id', $request->student);
            }
            if ($request->status) {
                $query->where('training_contract_status_id', $request->status);
            }
            if ($request->has('not_canceled')) {
                $query->whereNotIn('training_contracts.training_contract_status_id', [4, 5, 6]);
            }

            $query->groupBy('training_contracts.id', 'training_contracts.number_cfa')
                ->orderBy('training_contracts.beginning', 'desc');

            $items = $query->get();

            $fmtDate = function ($v) {
                if (!$v) return '';
                try {
                    return Carbon::parse($v)->format('d-m-Y');
                } catch (\Exception $e) {
                    return '';
                }
            };

            $rows = $items->map(function ($tc) use ($fmtDate) {
                $studentName = trim(
                    (string) data_get($tc, 'student.name', '') . ' ' .
                    (string) data_get($tc, 'student.surname', '')
                );

                $collabName = trim(
                    (string) data_get($tc, 'collaborator.name', '') . ' ' .
                    (string) data_get($tc, 'collaborator.surname', '')
                );

                return [
                    (string) data_get($tc, 'number_cfa', ''),
                    (string) data_get($tc, 'company.name', ''),
                    $studentName,
                    (string) data_get($tc, 'trainingContractStatus.name', ''),
                    (string) data_get($tc, 'provider.name', ''),
                    $fmtDate(data_get($tc, 'beginning')),
                    $fmtDate(data_get($tc, 'end')),
                    (string) data_get($tc, 'occupation.name', ''),
                    (string) data_get($tc, 'advisor.name', ''),
                    $collabName,
                ];
            });

            return Excel::download(
                new TrainingContractsExport($rows),
                'contratos_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
            );
        } catch (\Throwable $e) {
            \Log::error('Export contratos failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

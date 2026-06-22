<?php

namespace App\Http\Controllers\Api;
use App\Exports\BillsExport;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\BillResource;
use App\Models\AdvisorCommission;
use App\Models\AdvisorCommissionType;
use App\Models\Bill;
use App\Models\Chore;
use App\Models\CommissionType;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseOrigin;
use App\Models\CourseType;
use App\Models\Registration;
use App\Models\Student;
use App\Models\TrainingAction;
use App\Models\User;
use App\Models\UserCommission;
use App\Models\UserCommissionType;
use App\Services\AdvisorCommissionService;
use App\Services\UserCommissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class  BillController extends BaseController
{
    private $advisorCommissionService;
    private $userCommissionService;

    public function __construct( AdvisorCommissionService $advisorCommissionService, UserCommissionService $userCommissionService)
    {
        $this->advisorCommissionService = $advisorCommissionService;
        $this->userCommissionService = $userCommissionService;
    }

    /**
     * Obtener facturas
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $query = Bill::bill($mainCompanyId);

            $this->applyBillFilters($query, $request);

            // ✅ agrupa y orden
            $query->groupBy('billings.id');

            $sort = (string) $request->get('sort', '-year'); // default: año desc
            $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $key  = ltrim($sort, '-');

            switch ($key) {

                // ✅ Invoice number (columna de billings)
                case 'billing_number':
                    $query->orderBy('billings.billing_number', $dir);
                    break;

                // ✅ Course (lo que ves en UI: formative_action / group training_action.name)
                // Orden robusto: formative_action, group, training_action.name
                case 'course':
                    $query->orderByRaw("
        (SELECT ta.formative_action
         FROM courses c
         JOIN training_actions ta ON ta.id = c.training_action_id
         WHERE c.id = billings.course_id
         LIMIT 1
        ) {$dir}
    ");

                    $query->orderByRaw("
        (SELECT c.`group`
         FROM courses c
         WHERE c.id = billings.course_id
         LIMIT 1
        ) {$dir}
    ");

                    $query->orderByRaw("
        (SELECT ta.name
         FROM courses c
         JOIN training_actions ta ON ta.id = c.training_action_id
         WHERE c.id = billings.course_id
         LIMIT 1
        ) {$dir}
    ");
                    break;

                // ✅ Year (del beginning del curso)
                case 'year':
                    $query->orderByRaw(
                        "YEAR((
                SELECT c.beginning FROM courses c
                WHERE c.id = billings.course_id
                LIMIT 1
            )) {$dir}"
                    );
                    break;

                // ✅ Type (según tu UI: Bonificada/No bonificada)
                // is_bonus = 1 => Bonificada (si tu lógica es la estándar)
                case 'type':
                    $query->orderBy('billings.is_bonus', $dir);
                    break;

                // ✅ Company (companies.name)
                case 'company':
                    $query->orderBy(
                        Company::select('name')
                            ->whereColumn('companies.id', 'billings.company_id')
                            ->limit(1),
                        $dir
                    );
                    break;

                // ✅ Invoiced (billings.invoiced 0/1)
                case 'invoiced':
                    $query->orderBy('billings.invoiced', $dir);
                    break;

                // ✅ Bonus sent (si tienes bonus_sent / bonus_sent_status / bonus_status etc.)
                // Ajusta el campo real: aquí pongo bonus_sent como ejemplo
                case 'bonus_status':
                    $query->orderBy('billings.bonus_status', $dir);
                    break;

                // ✅ Charged (billings.charged 0/1)
                case 'charged':
                    $query->orderBy('billings.charged', $dir);
                    break;

                // ✅ Invoice (tu columna real: remitted / invoice / etc.)
                // Ajusta: yo pongo remitted por tu comentario.
                case 'invoice':
                    $query->orderBy('billings.remitted', $dir);
                    break;

                default:
                    // default estable
                    $query->orderByDesc(
                        Course::select('beginning')
                            ->whereColumn('courses.id', 'billings.course_id')
                            ->limit(1)
                    );
                    break;
            }

            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                return $this->sendResponse(
                    [
                        'bills' => BillResource::collection($paginator),
                        'links' => GeneralHelpers::generatePaginationData($paginator)['links'],
                        'meta'  => GeneralHelpers::generatePaginationData($paginator)['meta'],
                    ],
                    trans('Obtenido')
                );
            }

            return $this->sendResponse(
                ['bills' => BillResource::collection($query->get())],
                trans('Obtenido con éxito')
            );

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener factura
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $bill = Bill::bill($mainCompanyId)
            ->where('billings.id', $id)
            ->first();
        if ($bill) {
            $course = Course::where('id', $bill->course_id)->first();
            $company = Company::where('id', $bill->company_id)->first();
            $bill['name'] = $course->name .' - '. $company->name .' '.Carbon::parse($course->beginning)->format('d/m/Y') .' - '.Carbon::parse($course->end)->format('d/m/Y');
            return $this->sendResponse(
                [
                    'bill' => $bill,
                ],
                trans('Obtenido con éxito')
            );
        }
        return response()->json([
            'status' => 400,
            'message' => 'Factura no existe'
        ]);
    }

    /**
     * Crear factura
     * @param Request $request
     * @return void
     */
    public function store(Request $request){

    }

    /**
     * Editar factura
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        try {
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $bill = Bill::where('billings.id', $id)
                ->FilterMainCompany($mainCompanyId)
                ->first();

            if (!$bill) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Factura no existe'
                ]);
            }
            $request['is_bonus'] = $bill->is_bonus;
            $data = $request->all();
            $data['course_id'] = $bill->course_id;
            $data['main_company_id'] = $mainCompanyId;
            $element = $bill->updateWithService($data);

            // Buscamos el curso de que pertenece esta matriculación
            $course = Course::where('id' , $bill->course_id)
                ->FilterMainCompany($mainCompanyId)
                ->first();
            if ($course) {
                $commissionType = null;
                // Obtenemos la acción formativa para ver que origen tiene
                $trainingAction = TrainingAction::find($course->training_action_id);
                if ($trainingAction->course_origin_id) {
                    // Vemos si existe un tipo de comisión con el nombre de origen
                    $courseOrigin = CourseOrigin::find($trainingAction->course_origin_id);
                    $commissionType = CommissionType::where('name', $courseOrigin->name)
                        ->first();
                }
                // Si no existe ya miramos el tipo de curso para crear la comisión
                if (!$commissionType) {
                    $courseType = CourseType::find($course->course_type_id);
                    $commissionType = CommissionType::where('name', $courseType->name)
                        ->first();
                }
                if ($commissionType) {
                    // Para crear las comisiones primero tenemos que asegurar que tiene una asesoría
                    if ($bill->advisor_id) {
                        $percentage = $commissionType->percentage;
                        $advisorCommission = AdvisorCommission::where('commissionable_id', $bill->id)
                            ->where('commissionable_type', 'App\Models\Bill')
                            ->where('advisor_id', $bill->advisor_id)
                            ->first();
                        $advisorCommissionType = AdvisorCommissionType::where('advisor_id', $bill->advisor_id)
                            ->where('commission_type_id', $commissionType->id)->first();
                        if ($advisorCommissionType) {
                            $percentage = $advisorCommissionType->percentage;
                        }
                        $commissionData = [
                            'advisor_id' => $bill->advisor_id,
                            'course_id' => $bill->course_id,
                            'commissionable_id' => $bill->id,
                            'commissionable_type' => 'App\Models\Bill',
                            'commission_type_id' => $commissionType->id,
                            'percentage' => $percentage,
                            'amount' => ($percentage / 100) * $bill->billing,
                            'bill_amount' => $bill->billing,
                            'main_company_id' => $mainCompanyId,
                        ];
                        if ($advisorCommission) {
                            $this->advisorCommissionService->update($advisorCommission, $commissionData);
                        } else {
                            $this->advisorCommissionService->create($commissionData);
                        }
                    }
                    if ($bill->collaborator_id) {
                        $percentage = $commissionType->percentage;
                        $userCommission = UserCommission::where('commissionable_id', $bill->id)
                            ->where('commissionable_type', 'App\Models\Bill')
                            ->where('user_id', $bill->collaborator_id)
                            ->first();
                        $userCommissionType = UserCommissionType::where('user_id', $bill->collaborator_id)
                            ->where('commission_type_id', $commissionType->id)->first();
                        if ($userCommissionType) {
                            $percentage = $userCommissionType->percentage;
                        }
                        $commissionData = [
                            'user_id' => $bill->collaborator_id,
                            'course_id' => $bill->course_id,
                            'commissionable_id' => $bill->id,
                            'commissionable_type' => 'App\Models\Bill',
                            'commission_type_id' => $commissionType->id,
                            'percentage' => $percentage,
                            'amount' => ($percentage / 100) * $bill->billing,
                            'bill_amount' => $bill->billing,
                            'main_company_id' => $mainCompanyId,
                        ];
                        if ($userCommission) {
                            $this->userCommissionService->update($userCommission, $commissionData);
                        } else {
                            $this->userCommissionService->create($commissionData);
                        }
                    }
                }
            }

            $registrations = Registration::billingRegistration($id, $mainCompanyId)
                ->get();
            foreach ($registrations as $registration){
                $chore = Chore::where('id', $registration->chore_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();

                if ($chore) {
                    $parseDate = function ($v) {
                        if (empty($v)) return null;

                        // si viene con hora, nos quedamos con la parte de fecha
                        $v = substr((string)$v, 0, 10);

                        foreach (['Y-m-d', 'd-m-Y'] as $fmt) {
                            try {
                                return \Illuminate\Support\Carbon::createFromFormat($fmt, $v)->format('Y-m-d');
                            } catch (\Exception $e) {}
                        }

                        // último intento (más permisivo)
                        try {
                            return Carbon::parse($v)->format('Y-m-d');
                        } catch (\Exception $e) {
                            return null;
                        }
                    };

                    $chore->update([
                        'bonus_sent_status' => $bill['invoiced'],
                        'bonus_sent_date' => $parseDate($request['billing_date'] ?? null),
                        'invoiced_status' => $bill['invoiced'],
                        'invoiced_date' => $parseDate($request['billing_date'] ?? null),
                        'main_company_id' => $mainCompanyId,
                    ]);
                }
            }

            $bill = Bill::bill($mainCompanyId)
                ->where('billings.id', $element->id)
                ->first();
            if ($bill) {
                $course = Course::where('id', $bill->course_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                $company = Company::where('id', $bill->company_id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                $bill['name'] = $course->group . '/' . $course->name . ' - ' . $company->name . ' ' . Carbon::parse($course->beginning)->format('d/m/Y') . ' - ' . Carbon::parse($course->end)->format('d/m/Y');
            }

            return $this->sendResponse(
                [
                    'bill' => $bill,
                ],
                trans('Actualizado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminamos factura
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
                $bill = Bill::where('id', $id)
                    ->FilterMainCompany($mainCompanyId)
                    ->first();
                if ($bill) {
                    $advisorCommissions = AdvisorCommission::where('commissionable_id', $bill->id)
                        ->where('commissionable_type', 'App\Models\Bill')
                        ->get();
                    foreach ($advisorCommissions as $advisorCommission) {
                        AdvisorCommission::destroy($advisorCommission->id);
                    }
                }
                Bill::destroy($id);
                return $this->sendResponse(
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
     * Obtenemos los alumnos de la factura
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBillStudents($id, Request $request)
    {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $students = Student::billedStudent($id, $mainCompanyId)->get();
        return $this->sendResponse(
            [
                'students' => $students,
            ],
            trans('Obtenido con éxito')
        );
    }

    public function minYear(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            // Solo billings de la compañía principal
            // y con curso válido para sacar el YEAR(courses.beginning)
            $minYear = DB::table('billings')
                ->join('courses', 'courses.id', '=', 'billings.course_id')
                ->where('billings.main_company_id', $mainCompanyId)
                ->whereNotNull('courses.beginning')
                ->min(DB::raw('YEAR(courses.beginning)'));

            // fallback: si no hay nada, año actual
            $minYear = $minYear ?: (int)date('Y');

            return $this->sendResponse(
                ['minYear' => (int)$minYear],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = Bill::bill($mainCompanyId);

            $this->applyBillFilters($query, $request);

            $user = User::where('id', Auth::id())
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if ($user && $user->teacher_id) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->teacher_id);
                });
            }

            // orden (como sueles hacer)
            $query->orderByDesc('id');

            $items = $query->get();

            $yesNo = fn($v) => ((string)$v === '1' || $v === 1 || $v === true) ? 'Sí' : 'No';

            $num = function ($v) {
                if ($v === null || $v === '') return '';
                $n = (float) $v;
                return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
            };

            $yearFromCourseBeginning = function ($bill) {
                $begin = data_get($bill, 'course.beginning');
                if (!$begin) return '';
                try {
                    return \Carbon\Carbon::parse($begin)->format('Y');
                } catch (\Exception $e) {
                    return '';
                }
            };

            $courseLabel = function ($bill) {
                $fa    = (string) data_get($bill, 'course.trainingAction.formative_action', '');
                $group = (string) data_get($bill, 'course.group', '');
                $name  = (string) data_get($bill, 'course.trainingAction.name', '');

                $label = trim($fa . ' / ' . $group . ' ' . $name);

                return $label;
            };

            $rows = $items->map(function ($b) use ($yesNo, $num, $courseLabel, $yearFromCourseBeginning) {
                $collabFull = trim(
                    (string) data_get($b, 'collaborator.name', '') . ' ' .
                    (string) data_get($b, 'collaborator.surname', '')
                );

                return [
                    (string) data_get($b, 'billing_number', ''),            // Factura
                    $courseLabel($b),                                       // Curso
                    $yearFromCourseBeginning($b),                           // Año (desde course.beginning)
                    (string) data_get($b, 'payment.name', ''),              // Tipo Factura
                    (string) data_get($b, 'company.name', ''),              // Empresa
                    (string) data_get($b, 'advisor.name', ''),              // Asesor
                    $collabFull,                                            // Colaborador
                    (string) data_get($b, 'number_students', ''),           // Nº Alumnos
                    $num(data_get($b, 'billing', '')),                      // Total
                    $num(data_get($b, 'bonus', '')),                        // Total Bonificado
                    $num(data_get($b, 'expenses', '')),                     // Total No Bonificado (según tu campo)
                    $yesNo(data_get($b, 'charged', '')),                    // Pagada
                    (string) data_get($b, 'bonus_status', ''),              // Estado
                    $yesNo(data_get($b, 'remitted', '')),                   // Verificada (remitted)
                ];
            });

            return Excel::download(new BillsExport($rows), 'Facturas.xlsx');

        } catch (\Exception $e) {
            \Log::error("Bills exportExcel error: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function normalizeBooleanFilter($value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value) || is_int($value)) {
            return (int) $value;
        }

        $normalized = mb_strtolower(trim((string) $value));

        if ($normalized === '') {
            return null;
        }

        return match ($normalized) {
            '1', 'si', 'sí', 'true' => 1,
            '0', 'no', 'false' => 0,
            default => null,
        };
    }

    protected function normalizeChargedFilter(Request $request): ?int
    {
        $value = $request->has('paid')
            ? $request->input('paid')
            : $request->input('charged');

        return $this->normalizeBooleanFilter($value);
    }

    protected function applyBillFilters($query, Request $request): void
    {
        $cfa = CourseType::where('name', 'CFA')->first();

        if ($cfa) {
            $query->whereHas('course', fn ($q) => $q->where('course_type_id', '!=', $cfa->id));
        }

        if ($request->filled('course')) {
            $course = $request->course;

            $query->whereHas('course', function ($q) use ($course) {
                if (is_numeric($course)) {
                    $q->where('courses.id', (int) $course);
                    return;
                }

                $q->whereHas('trainingAction', function ($ta) use ($course) {
                    $ta->where('training_actions.name', 'like', "%{$course}%")
                        ->orWhere('training_actions.formative_action', 'like', "%{$course}%");
                });
            });
        }

        if ($request->filled('company')) {
            $query->where('billings.company_id', $request->company);
        }

        if ($request->filled('advisor')) {
            $query->where('billings.advisor_id', $request->advisor);
        }

        if ($request->filled('collaborator')) {
            $query->where('billings.collaborator_id', $request->collaborator);
        }

        if ($request->filled('payment')) {
            $query->where('billings.payment_id', $request->payment);
        }

        if ($request->filled('type')) {
            if ($request->type == 1) {
                $query->where('billings.is_bonus', 1);
            } elseif ($request->type == 0) {
                $query->where('billings.is_bonus', 0);
            }
        }

        $invoiced = $this->normalizeBooleanFilter($request->input('invoiced'));
        if ($invoiced !== null) {
            $query->where('billings.invoiced', $invoiced);
        }

        $charged = $this->normalizeChargedFilter($request);
        if ($charged !== null) {
            $query->where('billings.charged', $charged);
        }

        if ($request->filled('year')) {
            $year = (int) $request->year;

            $query->whereHas('course', function ($q) use ($year) {
                $q->whereYear('beginning', $year);
            });
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Tracing;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    private const EXCLUDED_CALENDAR_TRAINING_CONTRACT_STATUSES = ['BAJA', 'BAJA IT', 'NO FORMALIZADO'];

    public function coursesMetric(Request $request)
    {
        $mainCompanyId = $this->resolveMainCompanyId($request);
        $query = $this->baseCoursesQuery($mainCompanyId)
            ->leftJoin('course_types', 'course_types.id', '=', 'courses.course_type_id')
            ->selectRaw("YEAR(courses.beginning) AS year, COALESCE(course_types.name, 'Others') AS course_type, COUNT(*) AS total")
            ->whereNotNull('courses.beginning')
            ->groupByRaw("YEAR(courses.beginning), COALESCE(course_types.name, 'Others')")
            ->orderByRaw('YEAR(courses.beginning) ASC')
            ->orderBy('course_type');

        $rows = $query->get();
        $years = $rows->pluck('year')
            ->filter()
            ->map(fn ($year) => (string) $year)
            ->unique()
            ->values()
            ->all();

        $types = $rows->pluck('course_type')->unique()->values()->all();
        $series = collect($types)->map(function ($type) use ($rows, $years) {
            return [
                'name' => $type,
                'data' => collect($years)->map(function ($year) use ($rows, $type) {
                    $row = $rows->first(fn ($item) => (string) $item->year === (string) $year && $item->course_type === $type);

                    return (int) ($row->total ?? 0);
                })->values()->all(),
            ];
        })->values()->all();

        return $this->sendResponse([
            'years' => $years,
            'series' => $series,
        ], trans('Obtenido con éxito'));
    }

    public function registrationsMetric(Request $request)
    {
        $mainCompanyId = $this->resolveMainCompanyId($request);
        $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $rows = Registration::query()
            ->join('courses', 'courses.id', '=', 'registrations.course_id')
            ->where('registrations.main_company_id', $mainCompanyId)
            ->whereNotNull('courses.beginning')
            ->selectRaw('YEAR(courses.beginning) AS year, MONTH(courses.beginning) AS month_number, SUM(COALESCE(registrations.price, 0)) AS total')
            ->groupByRaw('YEAR(courses.beginning), MONTH(courses.beginning)')
            ->orderByRaw('YEAR(courses.beginning) ASC, MONTH(courses.beginning) ASC')
            ->get();

        $years = $rows->pluck('year')
            ->filter()
            ->map(fn ($year) => (string) $year)
            ->unique()
            ->values()
            ->all();

        $yearlySeries = collect($years)->map(function ($year) use ($rows) {
            return [
                'name' => "Ventas en {$year} (€)",
                'data' => collect(range(1, 12))->map(function ($month) use ($rows, $year) {
                    $row = $rows->first(fn ($item) => (string) $item->year === (string) $year && (int) $item->month_number === $month);

                    return round((float) ($row->total ?? 0), 2);
                })->values()->all(),
            ];
        })->values()->all();

        $totalsByYear = collect($years)->map(function ($year) use ($rows) {
            return [
                'year' => $year,
                'total' => round((float) $rows->where('year', (int) $year)->sum('total'), 2),
            ];
        })->values()->all();

        return $this->sendResponse([
            'months' => $months,
            'yearly_series' => $yearlySeries,
            'totals_by_year' => $totalsByYear,
        ], trans('Obtenido con éxito'));
    }

    public function liveTrainingContracts(Request $request)
    {
        $mainCompanyId = $this->resolveMainCompanyId($request);
        $user = Auth::user();
        $maxDate = Carbon::now()->startOfMonth();
        $minDate = Carbon::now()->startOfMonth()->subMonths(11);
        $months = [];
        $cursor = $minDate->copy();

        while ($cursor->lte($maxDate)) {
            $months[] = $cursor->copy();
            $cursor->addMonth();
        }

        $categories = collect($months)->map(fn ($month) => $month->format('Y-m'))->all();
        $data = collect($months)->map(function ($month) use ($mainCompanyId, $user) {
            $monthStart = $month->copy()->startOfMonth()->format('Y-m-d');
            $monthEnd = $month->copy()->endOfMonth()->format('Y-m-d');

            $query = TrainingContract::query()
                ->where('training_contracts.main_company_id', $mainCompanyId)
                ->whereIn('training_contracts.training_contract_status_id', [2, 4])
                ->whereNotNull('training_contracts.beginning')
                ->whereDate('training_contracts.beginning', '<=', $monthEnd)
                ->where(function ($q) use ($monthStart) {
                    $q->where(function ($activeQuery) use ($monthStart) {
                        $activeQuery->where('training_contracts.training_contract_status_id', 2)
                            ->whereNotNull('training_contracts.end')
                            ->whereDate('training_contracts.end', '>=', $monthStart);
                    })->orWhere(function ($leaveQuery) use ($monthStart) {
                        $leaveQuery->where('training_contracts.training_contract_status_id', 4)
                            ->whereNotNull('training_contracts.on_leave_date')
                            ->whereDate('training_contracts.on_leave_date', '>=', $monthStart);
                    });
                });

            if ($user?->teacher_id) {
                $query->whereExists(function ($subQuery) use ($user) {
                    $subQuery->select(DB::raw(1))
                        ->from('training_contract_elements')
                        ->join('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                        ->whereColumn('training_contract_elements.training_contract_id', 'training_contracts.id')
                        ->where('courses.teacher_id', $user->teacher_id);
                });
            }

            return (int) $query->count('training_contracts.id');
        })->all();

        return $this->sendResponse([
            'categories' => $categories,
            'data' => $data,
        ], trans('Obtenido con éxito'));
    }

    public function liveCourses(Request $request)
    {
        $mainCompanyId = $this->resolveMainCompanyId($request);
        $currentYear = (int) now()->format('Y');
        $lastYear = $currentYear - 1;

        $rows = $this->baseCoursesQuery($mainCompanyId)
            ->whereNotNull('courses.beginning')
            ->whereRaw('YEAR(courses.beginning) IN (?, ?)', [$currentYear, $lastYear])
            ->selectRaw('YEAR(courses.beginning) AS year, COUNT(*) AS total')
            ->groupByRaw('YEAR(courses.beginning)')
            ->get()
            ->keyBy('year');

        return $this->sendResponse([
            'labels' => [(string) $currentYear, (string) $lastYear],
            'data' => [
                (int) ($rows[$currentYear]->total ?? 0),
                (int) ($rows[$lastYear]->total ?? 0),
            ],
        ], trans('Obtenido con éxito'));
    }

    public function advisorsCommissionsTop(Request $request)
    {
        $mainCompanyId = $this->resolveMainCompanyId($request);
        $rows = DB::table('advisor_commissions')
            ->join('advisors', 'advisors.id', '=', 'advisor_commissions.advisor_id')
            ->where('advisor_commissions.main_company_id', $mainCompanyId)
            ->where('advisors.main_company_id', $mainCompanyId)
            ->selectRaw('advisors.id, advisors.name, SUM(COALESCE(advisor_commissions.amount, 0)) AS total')
            ->groupBy('advisors.id', 'advisors.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return $this->sendResponse([
            'categories' => $rows->pluck('name')->values()->all(),
            'data' => $rows->map(fn ($row) => round((float) $row->total, 2))->values()->all(),
        ], trans('Obtenido con éxito'));
    }

    public function usersCommissionsTop(Request $request)
    {
        $mainCompanyId = $this->resolveMainCompanyId($request);
        $rows = DB::table('user_commissions')
            ->join('users', 'users.id', '=', 'user_commissions.user_id')
            ->where('user_commissions.main_company_id', $mainCompanyId)
            ->where('users.main_company_id', $mainCompanyId)
            ->selectRaw("users.id, CONCAT(users.name, ' ', COALESCE(users.surname, '')) AS full_name, SUM(COALESCE(user_commissions.amount, 0)) AS total")
            ->groupBy('users.id', 'users.name', 'users.surname')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return $this->sendResponse([
            'categories' => $rows->pluck('full_name')->map(fn ($name) => trim($name))->values()->all(),
            'data' => $rows->map(fn ($row) => round((float) $row->total, 2))->values()->all(),
        ], trans('Obtenido con éxito'));
    }

    public function calendarEvents(Request $request)
    {
        $validated = $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date'],
        ]);

        $mainCompanyId = $this->resolveMainCompanyId($request);
        $user = Auth::user();
        $from = Carbon::parse($validated['from'])->startOfDay()->format('Y-m-d');
        $to = Carbon::parse($validated['to'])->endOfDay()->format('Y-m-d');

        $tracingsQuery = Tracing::tracing($mainCompanyId);
        if ($user?->teacher_id) {
            $tracingsQuery->where('courses.teacher_id', $user->teacher_id);
        }
        $this->applyCalendarDateFilter($tracingsQuery, $from, $to);
        $tracings = $tracingsQuery->orderBy('tracings.id', 'desc')->get();

        $coursesQuery = Course::query()
            ->with([
                'courseType:id,name',
                'courseStatus:id,name',
                'registrations' => function ($query) use ($mainCompanyId) {
                    $query->where('registrations.main_company_id', $mainCompanyId)
                        ->with([
                            'student:id,name,surname',
                            'company:id,name',
                            'tracing:id',
                        ]);
                },
            ])
            ->where('courses.main_company_id', $mainCompanyId)
            ->where('courses.course_status_id', '!=', 4)
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween(DB::raw('DATE(courses.beginning)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(courses.end)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(courses.welcome_date)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(courses.quarter_date)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(courses.half_date)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(courses.three_quarters_date)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(courses.final_date)'), [$from, $to]);
            })
            ->whereHas('registrations', function ($query) use ($mainCompanyId) {
                $query->where('registrations.main_company_id', $mainCompanyId);
            });

        if ($user?->teacher_id) {
            $coursesQuery->where('courses.teacher_id', $user->teacher_id);
        }

        $courses = $coursesQuery->get();

        $elementsQuery = TrainingContractElement::getAllTrainingContractElements($mainCompanyId)
            ->leftJoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
            ->whereNotIn(DB::raw('UPPER(training_contract_statuses.name)'), self::EXCLUDED_CALENDAR_TRAINING_CONTRACT_STATUSES)
            ->whereDate('training_contract_elements.end', '>=', $from)
            ->whereDate('training_contract_elements.beginning', '<=', $to);

        if ($user?->teacher_id) {
            $elementsQuery->leftJoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                ->where('courses.teacher_id', $user->teacher_id)
                ->groupBy(
                    'training_contract_elements.id',
                    'training_contract_elements.certification_id',
                    'training_contract_elements.training_action_id',
                    'training_contract_elements.training_contract_id',
                    'training_contract_elements.beginning',
                    'training_contract_elements.end',
                    'training_contract_elements.total_days',
                    'training_contract_elements.order',
                    'training_contract_elements.course_id',
                    'training_contract_elements.training_tutor',
                    'training_contract_elements.training_tutor_dni',
                    'training_contract_elements.main_company_id',
                    'certifications.name',
                    'certifications.total_hours',
                    'training_contracts.number_cfa',
                    'training_actions.formative_action',
                    'training_actions.name',
                    'training_actions.total_hours',
                    'training_actions.face_to_face_hours',
                    'training_actions.teletraining_hours',
                    'certifications.face_to_face_hours',
                    'certifications.teletraining_hours'
                );
        }

        $elements = $elementsQuery->get();

        $contractsQuery = TrainingContract::query()
            ->select(
                'training_contracts.id',
                'training_contracts.company_id',
                'training_contracts.student_id',
                'training_contracts.beginning',
                'training_contracts.end',
                'training_contracts.beginning_formation',
                'training_contracts.end_formation',
                'training_contracts.formation_hours',
                'training_contracts.formative_hours_first_year',
                'training_contracts.formative_hours_second_year',
                'training_contracts.total_hours',
                'training_contracts.training_contract_status_id',
                'training_contracts.main_company_id'
            )
            ->with([
                'company:id,name',
                'student:id,name,surname',
            ])
            ->leftJoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
            ->where('training_contracts.main_company_id', $mainCompanyId)
            ->whereNotIn(DB::raw('UPPER(training_contract_statuses.name)'), self::EXCLUDED_CALENDAR_TRAINING_CONTRACT_STATUSES)
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween(DB::raw('DATE(training_contracts.beginning_formation)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(training_contracts.beginning)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(training_contracts.end_formation)'), [$from, $to])
                    ->orWhereBetween(DB::raw('DATE(training_contracts.end)'), [$from, $to]);
            });

        if ($user?->teacher_id) {
            $contractsQuery->leftJoin('training_contract_elements', 'training_contract_elements.training_contract_id', '=', 'training_contracts.id')
                ->leftJoin('courses', 'courses.id', '=', 'training_contract_elements.course_id')
                ->where('courses.teacher_id', $user->teacher_id)
                ->groupBy(
                    'training_contracts.id',
                    'training_contracts.company_id',
                    'training_contracts.student_id',
                    'training_contracts.beginning',
                    'training_contracts.end',
                    'training_contracts.beginning_formation',
                    'training_contracts.end_formation',
                    'training_contracts.formation_hours',
                    'training_contracts.formative_hours_first_year',
                    'training_contracts.formative_hours_second_year',
                    'training_contracts.total_hours',
                    'training_contracts.training_contract_status_id',
                    'training_contracts.main_company_id'
                );
        }

        $contracts = $contractsQuery->get();

        $tracingContractsByPair = $this->getTracingContractsByPair($tracings);

        $allTracingEvents = $this->buildTracingEvents($tracings, $from, $to);
        $tracingEvents = $this->buildTracingEvents($tracings, $from, $to, $tracingContractsByPair);
        $tracingEvents = [
            ...$tracingEvents,
            ...$this->buildCourseTracingFallbackEvents($courses, $from, $to, $this->getExistingTracingEventKeys($allTracingEvents)),
        ];
        $trainingContractEvents = [
            ...$this->buildTrainingElementEvents($elements, $from, $to),
            ...$this->buildTrainingContractMainEvents($contracts, $from, $to),
        ];
        $mainContractEvents = $this->buildMainContractEvents($contracts, $from, $to);

        return $this->sendResponse([
            'tracing_events' => $tracingEvents,
            'training_contract_events' => $trainingContractEvents,
            'main_contract_events' => $mainContractEvents,
        ], trans('Obtenido con éxito'));
    }

    public function tracingNotifications(Request $request)
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $mainCompanyId = $this->resolveMainCompanyId($request);
        $user = Auth::user();
        $date = Carbon::parse($validated['date'] ?? now())->format('Y-m-d');

        $tracingsQuery = Tracing::tracing($mainCompanyId);
        if ($user?->teacher_id) {
            $tracingsQuery->where('courses.teacher_id', $user->teacher_id);
        }
        $this->applyCalendarDateFilter($tracingsQuery, $date, $date);

        $tracings = $tracingsQuery->orderBy('tracings.id', 'desc')->get();
        $notifications = [];

        foreach ($tracings as $tracing) {
            if ((int) ($tracing->course_status_id ?? $tracing->course?->course_status_id ?? 0) === 4) {
                continue;
            }

            $dates = [
                ['label' => 'Fecha Fin', 'value' => $this->getTracingDateByKey($tracing, 'final'), 'check' => (int) ($tracing->final_message ?? 0)],
                ['label' => '75%', 'value' => $this->getTracingDateByKey($tracing, 'three_quarters'), 'check' => (int) ($tracing->three_quarters_message ?? 0)],
                ['label' => '50%', 'value' => $this->getTracingDateByKey($tracing, 'half'), 'check' => (int) ($tracing->half_message ?? 0)],
                ['label' => 'Fecha Bienvenida', 'value' => $this->getTracingDateByKey($tracing, 'welcome'), 'check' => (int) ($tracing->welcome_message ?? 0)],
                ['label' => '25%', 'value' => $this->getTracingDateByKey($tracing, 'quarter'), 'check' => (int) ($tracing->quarter_message ?? 0)],
            ];

            foreach ($dates as $entry) {
                if ($entry['value'] !== $date || $entry['check'] !== 0) {
                    continue;
                }

                $notifications[] = [
                    'id' => (int) $tracing->id,
                    'title' => trim(($tracing->student_name ?? $tracing->student?->name ?? '') . ' ' . ($tracing->student_surname ?? $tracing->student?->surname ?? '')),
                    'subtitle' => $entry['label'],
                    'date' => $entry['value'],
                ];
            }
        }

        return $this->sendResponse([
            'notifications' => $notifications,
        ], trans('Obtenido con éxito'));
    }

    private function resolveMainCompanyId(Request $request): int
    {
        return (int) GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
    }

    private function baseCoursesQuery(int $mainCompanyId)
    {
        $query = Course::query()->where('courses.main_company_id', $mainCompanyId);
        $user = Auth::user();

        if ($user?->teacher_id) {
            $query->where('courses.teacher_id', $user->teacher_id);
        } elseif ($user?->advisor_id) {
            $query->whereExists(function ($subQuery) use ($user) {
                $subQuery->select(DB::raw(1))
                    ->from('billings')
                    ->whereColumn('billings.course_id', 'courses.id')
                    ->where('billings.advisor_id', $user->advisor_id);
            });
        }

        return $query;
    }

    private function applyCalendarDateFilter($query, string $from, string $to): void
    {
        $tracingDateColumns = [
            'tracings.follow_up_date',
            'tracings.welcome_date_sent',
            'tracings.quarter_date_sent',
            'tracings.half_date_sent',
            'tracings.three_quarters_date_sent',
            'tracings.final_date_sent',
        ];

        $courseDateColumns = [
            'beginning',
            'end',
            'welcome_date',
            'quarter_date',
            'half_date',
            'three_quarters_date',
            'final_date',
        ];

        $query->where(function ($dateQuery) use ($tracingDateColumns, $courseDateColumns, $from, $to) {
            foreach ($tracingDateColumns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $dateQuery->{$method}(function ($q) use ($column, $from, $to) {
                    $q->whereDate($column, '>=', $from)
                        ->whereDate($column, '<=', $to);
                });
            }

            $dateQuery->orWhereHas('course', function ($courseQuery) use ($courseDateColumns, $from, $to) {
                $courseQuery->where(function ($courseDateQuery) use ($courseDateColumns, $from, $to) {
                    foreach ($courseDateColumns as $index => $column) {
                        $method = $index === 0 ? 'where' : 'orWhere';
                        $courseDateQuery->{$method}(function ($q) use ($column, $from, $to) {
                            $q->whereDate($column, '>=', $from)
                                ->whereDate($column, '<=', $to);
                        });
                    }
                });
            });
        });
    }

    private function buildTracingEvents($tracings, string $from, string $to, array $tracingContractsByPair = []): array
    {
        $events = [];

        foreach ($tracings as $tracing) {
            if ((int) ($tracing->course_status_id ?? $tracing->course?->course_status_id ?? 0) === 4) {
                continue;
            }

            if ($this->shouldExcludeTracingFromTrainingContractStatus($tracing, $tracingContractsByPair)) {
                continue;
            }

            $courseType = $this->resolveCourseType($tracing);
            $studentLabel = trim(($tracing->student_name ?? $tracing->student?->name ?? '') . ' ' . ($tracing->student_surname ?? $tracing->student?->surname ?? ''));

            $definitions = [
                ['kind' => 'start', 'label' => 'Inicio curso', 'message' => 0],
                ['key' => 'quarter', 'label' => '25%', 'message' => (int) ($tracing->quarter_message ?? 0)],
                ['key' => 'half', 'label' => '50%', 'message' => (int) ($tracing->half_message ?? 0)],
                ['key' => 'three_quarters', 'label' => '75%', 'message' => (int) ($tracing->three_quarters_message ?? 0)],
                ['kind' => 'end', 'label' => 'Fin curso', 'message' => (int) ($tracing->final_message ?? 0)],
            ];

            foreach ($definitions as $definition) {
                $date = match ($definition['kind'] ?? 'key') {
                    'start' => $this->firstValidYmd($tracing->course?->beginning ?? null),
                    'end' => $this->firstValidYmd($tracing->course?->end ?? null, $this->getTracingDateByKey($tracing, 'final')),
                    default => $this->getTracingDateByKey($tracing, $definition['key']),
                };
                if (!$this->isDateInRange($date, $from, $to)) {
                    continue;
                }

                $events[] = [
                    'title' => trim("{$studentLabel} - {$definition['label']}"),
                    'start' => $date,
                    'color' => $this->getCourseTypeColor($courseType, $definition['message']),
                    'type' => 'tracing',
                    'meta' => [
                        'tracingId' => $tracing->id,
                        'tracing' => $this->serializeTracingForCalendar($tracing),
                        'dateKind' => $definition['kind'] ?? $definition['key'],
                    ],
                ];
            }
        }

        return $events;
    }

    private function buildCourseTracingFallbackEvents($courses, string $from, string $to, array $existingKeys = []): array
    {
        $events = [];
        $seenKeys = array_fill_keys($existingKeys, true);

        foreach ($courses as $course) {
            if ((int) ($course->course_status_id ?? 0) === 4) {
                continue;
            }

            $courseType = trim((string) ($course->courseType?->name ?? ''));
            $courseBeginning = $this->firstValidYmd($course->beginning ?? null);
            $courseQuarter = $this->firstValidYmd($course->quarter_date ?? null);
            $courseHalf = $this->firstValidYmd($course->half_date ?? null);
            $courseThreeQuarters = $this->firstValidYmd($course->three_quarters_date ?? null);
            $courseEnd = $this->firstValidYmd($course->end ?? null, $course->final_date ?? null);

            foreach (($course->registrations ?? []) as $registration) {
                if ($registration->tracing_id && $registration->tracing) {
                    continue;
                }

                $studentName = trim(($registration->student?->name ?? '') . ' ' . ($registration->student?->surname ?? ''));

                if ($studentName === '') {
                    continue;
                }

                $definitions = [
                    ['kind' => 'start', 'label' => 'Inicio curso', 'date' => $courseBeginning],
                    ['kind' => 'quarter', 'label' => '25%', 'date' => $courseQuarter],
                    ['kind' => 'half', 'label' => '50%', 'date' => $courseHalf],
                    ['kind' => 'three_quarters', 'label' => '75%', 'date' => $courseThreeQuarters],
                    ['kind' => 'end', 'label' => 'Fin curso', 'date' => $courseEnd],
                ];

                foreach ($definitions as $definition) {
                    $date = $definition['date'] ?? '';
                    if (!$this->isDateInRange($date, $from, $to)) {
                        continue;
                    }

                    $eventKey = $this->buildCalendarStudentEventKey(
                        $date,
                        (string) ($definition['kind'] ?? ''),
                        (int) ($course->id ?? 0),
                        (int) ($registration->student_id ?? 0),
                        (int) ($registration->company_id ?? 0)
                    );

                    if (isset($seenKeys[$eventKey])) {
                        continue;
                    }

                    $seenKeys[$eventKey] = true;
                    $events[] = [
                        'title' => trim("{$studentName} - {$definition['label']}"),
                        'start' => $date,
                        'color' => $this->getCourseTypeColor($courseType, 0),
                        'type' => 'tracing',
                        'meta' => [
                            'tracingId' => null,
                            'tracing' => $this->serializeFallbackCourseTracingForCalendar($course, $registration),
                            'dateKind' => $definition['kind'],
                        ],
                    ];
                }
            }
        }

        return $events;
    }

    private function getExistingTracingEventKeys(array $events): array
    {
        $keys = [];

        foreach ($events as $event) {
            $tracing = $event['meta']['tracing'] ?? null;
            if (!$tracing) {
                continue;
            }

            $date = $this->normalizeDate($event['start'] ?? null);
            $dateKind = (string) ($event['meta']['dateKind'] ?? '');
            $courseId = (int) ($tracing['course_id'] ?? 0);
            $studentId = (int) ($tracing['student_id'] ?? 0);
            $companyId = (int) ($tracing['company_id'] ?? 0);

            if ($date === '' || $dateKind === '' || $courseId <= 0 || $studentId <= 0) {
                continue;
            }

            $keys[] = $this->buildCalendarStudentEventKey($date, $dateKind, $courseId, $studentId, $companyId);
        }

        return array_values(array_unique($keys));
    }

    private function buildCalendarStudentEventKey(string $date, string $dateKind, int $courseId, int $studentId, int $companyId): string
    {
        return implode('|', [
            $date,
            $dateKind,
            $courseId,
            $studentId,
            $companyId,
        ]);
    }

    private function getTracingContractsByPair($tracings): array
    {
        $pairs = collect($tracings)
            ->filter(function ($tracing) {
                return is_null($tracing->training_contract_element_id ?? null)
                    && (int) ($tracing->student_id ?? 0) > 0
                    && (int) ($tracing->company_id ?? 0) > 0;
            })
            ->map(fn ($tracing) => [
                'student_id' => (int) $tracing->student_id,
                'company_id' => (int) $tracing->company_id,
            ])
            ->unique(fn ($pair) => $pair['student_id'] . '|' . $pair['company_id'])
            ->values();

        if ($pairs->isEmpty()) {
            return [];
        }

        $contracts = TrainingContract::query()
            ->select(
                'training_contracts.student_id',
                'training_contracts.company_id',
                'training_contracts.beginning',
                'training_contracts.end',
                'training_contracts.beginning_formation',
                'training_contracts.end_formation',
                'training_contracts.on_leave_date',
                'training_contract_statuses.name as status_name'
            )
            ->leftJoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
            ->where(function ($query) use ($pairs) {
                foreach ($pairs as $index => $pair) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $query->{$method}(function ($pairQuery) use ($pair) {
                        $pairQuery->where('training_contracts.student_id', $pair['student_id'])
                            ->where('training_contracts.company_id', $pair['company_id']);
                    });
                }
            })
            ->get();

        $grouped = [];

        foreach ($contracts as $contract) {
            $key = ((int) $contract->student_id) . '|' . ((int) $contract->company_id);
            $grouped[$key][] = [
                'start' => $this->firstValidYmd($contract->beginning_formation, $contract->beginning, $contract->on_leave_date),
                'end' => $this->firstValidYmd($contract->end_formation, $contract->end),
                'status' => strtoupper(trim((string) ($contract->status_name ?? ''))),
            ];
        }

        return $grouped;
    }

    private function shouldExcludeTracingFromTrainingContractStatus($tracing, array $tracingContractsByPair): bool
    {
        if (!is_null($tracing->training_contract_element_id ?? null)) {
            return false;
        }

        $studentId = (int) ($tracing->student_id ?? 0);
        $companyId = (int) ($tracing->company_id ?? 0);

        if ($studentId <= 0 || $companyId <= 0) {
            return false;
        }

        $contracts = $tracingContractsByPair[$studentId . '|' . $companyId] ?? [];
        if ($contracts === []) {
            return false;
        }

        $courseStart = $this->firstValidYmd(
            $tracing->course?->beginning ?? null,
            $tracing->follow_up_date ?? null,
            $this->getTracingDateByKey($tracing, 'welcome')
        );
        $courseEnd = $this->firstValidYmd(
            $tracing->course?->end ?? null,
            $this->getTracingDateByKey($tracing, 'final'),
            $this->getTracingDateByKey($tracing, 'three_quarters'),
            $this->getTracingDateByKey($tracing, 'half'),
            $this->getTracingDateByKey($tracing, 'quarter'),
            $courseStart
        );

        if ($courseStart === '') {
            return false;
        }

        $hasExcludedContract = false;
        $hasValidContract = false;

        foreach ($contracts as $contract) {
            $contractStart = $contract['start'] ?? '';
            $contractEnd = $contract['end'] ?? '';

            if ($contractStart === '') {
                continue;
            }

            if ($contractEnd !== '' && $contractEnd < $courseStart) {
                continue;
            }

            if ($courseEnd !== '' && $contractStart > $courseEnd) {
                continue;
            }

            $status = strtoupper(trim((string) ($contract['status'] ?? '')));
            if (in_array($status, self::EXCLUDED_CALENDAR_TRAINING_CONTRACT_STATUSES, true)) {
                $hasExcludedContract = true;
            } elseif ($status !== '') {
                $hasValidContract = true;
            }
        }

        return $hasExcludedContract && !$hasValidContract;
    }

    private function buildTrainingElementEvents($elements, string $from, string $to): array
    {
        $events = [];

        foreach ($elements as $element) {
            $studentName = trim(
                ($element->student_name ?? $element->student?->name ?? $element->training_contract?->student?->name ?? 'Desconocido') . ' ' .
                ($element->student_surname ?? $element->student?->surname ?? $element->training_contract?->student?->surname ?? '')
            );
            $studentName = $studentName !== '' ? $studentName : 'Desconocido';

            $start = $this->normalizeDate($element->beginning);
            $end = $this->normalizeDate($element->end);
            $trainingContractId = $element->training_contract_id ?? $element->training_contract?->id ?? $element->id;

            if ($this->isDateInRange($start, $from, $to)) {
                $visual = $this->getTaskEventVisual($element, $start);
                $events[] = [
                    'title' => "{$studentName} - Inicio",
                    'start' => $start,
                    'type' => 'trainingContract',
                    'color' => $visual['color'],
                    'textColor' => $visual['textColor'],
                    'meta' => [
                        'elementId' => $element->id,
                        'training_contract_id' => $trainingContractId,
                        'raw' => array_merge($this->serializeTrainingElementForCalendar($element), [
                            'event_label' => 'Inicio formacion',
                            'event_kind' => 'start',
                        ]),
                    ],
                ];
            }

            if ($this->isDateInRange($end, $from, $to)) {
                $visual = $this->getTaskEventVisual($element, $end);
                $events[] = [
                    'title' => "{$studentName} - Fin",
                    'start' => $end,
                    'type' => 'trainingContract',
                    'color' => $visual['color'],
                    'textColor' => $visual['textColor'],
                    'meta' => [
                        'elementId' => $element->id,
                        'training_contract_id' => $trainingContractId,
                        'raw' => array_merge($this->serializeTrainingElementForCalendar($element), [
                            'event_label' => 'Fin formacion',
                            'event_kind' => 'end',
                        ]),
                    ],
                ];
            }
        }

        return $events;
    }

    private function buildTrainingContractMainEvents($contracts, string $from, string $to): array
    {
        $events = [];

        foreach ($contracts as $contract) {
            $student = trim(
                ($contract->student_name ?? $contract->student?->name ?? 'Estudiante') . ' ' .
                ($contract->student_surname ?? $contract->student?->surname ?? '')
            );
            $student = $student !== '' ? $student : 'Estudiante';

            $start = $this->normalizeDate($contract->beginning_formation ?: $contract->beginning);
            $end = $this->normalizeDate($contract->end_formation ?: $contract->end);

            if ($this->isDateInRange($start, $from, $to)) {
                $visual = $this->getTaskEventVisual($contract, $start);
                $events[] = [
                    'title' => "{$student} - Inicio",
                    'start' => $start,
                    'type' => 'trainingContract',
                    'color' => $visual['color'],
                    'textColor' => $visual['textColor'],
                    'meta' => [
                        'training_contract_id' => $contract->id,
                        'raw' => array_merge($this->serializeTrainingContractForCalendar($contract), [
                            'event_label' => 'Inicio formacion',
                            'event_kind' => 'start',
                        ]),
                        'source' => 'trainingContractMain',
                    ],
                ];
            }

            if ($this->isDateInRange($end, $from, $to)) {
                $visual = $this->getTaskEventVisual($contract, $end);
                $events[] = [
                    'title' => "{$student} - Fin",
                    'start' => $end,
                    'type' => 'trainingContract',
                    'color' => $visual['color'],
                    'textColor' => $visual['textColor'],
                    'meta' => [
                        'training_contract_id' => $contract->id,
                        'raw' => array_merge($this->serializeTrainingContractForCalendar($contract), [
                            'event_label' => 'Fin formacion',
                            'event_kind' => 'end',
                        ]),
                        'source' => 'trainingContractMain',
                    ],
                ];
            }
        }

        return $events;
    }

    private function buildMainContractEvents($contracts, string $from, string $to): array
    {
        $events = [];

        foreach ($contracts as $contract) {
            $student = trim(
                ($contract->student_name ?? $contract->student?->name ?? 'Estudiante') . ' ' .
                ($contract->student_surname ?? $contract->student?->surname ?? '')
            );
            $student = $student !== '' ? $student : 'Estudiante';
            $company = trim((string) ($contract->company_name ?? $contract->company?->name ?? ''));
            $baseTitle = $company !== '' ? "({$company})" : '';

            $start = $this->normalizeDate($contract->beginning_formation ?: $contract->beginning);
            $end = $this->normalizeDate($contract->end_formation ?: $contract->end);

            if ($this->isDateInRange($start, $from, $to)) {
                $visual = $this->getTaskEventVisual($contract, $start);
                $events[] = [
                    'title' => trim("{$student} - Inicio contrato {$baseTitle}"),
                    'start' => $start,
                    'type' => 'mainContract',
                    'color' => $visual['color'],
                    'textColor' => $visual['textColor'],
                    'meta' => [
                        'training_contract_id' => $contract->id,
                        'raw' => array_merge($this->serializeTrainingContractForCalendar($contract), [
                            'event_label' => 'Inicio contrato',
                            'event_kind' => 'contract_start',
                        ]),
                    ],
                ];
            }

            if ($this->isDateInRange($end, $from, $to)) {
                $visual = $this->getTaskEventVisual($contract, $end);
                $events[] = [
                    'title' => trim("{$student} - Fin contrato {$baseTitle}"),
                    'start' => $end,
                    'type' => 'mainContract',
                    'color' => $visual['color'],
                    'textColor' => $visual['textColor'],
                    'meta' => [
                        'training_contract_id' => $contract->id,
                        'raw' => array_merge($this->serializeTrainingContractForCalendar($contract), [
                            'event_label' => 'Fin contrato',
                            'event_kind' => 'contract_end',
                        ]),
                    ],
                ];
            }
        }

        return $events;
    }

    private function getTracingDateByKey($tracing, string $key): string
    {
        return $this->firstValidYmd(
            $tracing->{$key . '_date'} ?? null,
            $tracing->{$key . '_date_base'} ?? null,
            $tracing->course?->{$key . '_date'} ?? null,
            $tracing->course?->{$key . '_date_base'} ?? null,
            $tracing->{$key . '_date_sent'} ?? null,
            $tracing->course?->{$key . '_date_sent'} ?? null,
            $key === 'welcome' ? ($tracing->follow_up_date ?? null) : null,
            $tracing->{$key} ?? null
        );
    }

    private function firstValidYmd(...$values): string
    {
        foreach ($values as $value) {
            $normalized = $this->normalizeDate($value);
            if ($normalized !== '') {
                return $normalized;
            }
        }

        return '';
    }

    private function normalizeDate($value): string
    {
        if (!$value) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function isDateInRange(?string $date, string $from, string $to): bool
    {
        if (!$date) {
            return false;
        }

        return $date >= $from && $date <= $to;
    }

    private function resolveCourseType($tracing): string
    {
        $direct = trim((string) (
            $tracing->course_type ??
            $tracing->course_type_name ??
            $tracing->course?->courseType?->name ??
            $tracing->course?->course_type?->name ??
            ''
        ));

        if ($direct !== '') {
            return $direct;
        }

        return match ((int) ($tracing->course_type_id ?? $tracing->course?->course_type_id ?? 0)) {
            1 => 'Bonificado',
            2 => 'Privado',
            3 => 'Oferta',
            4 => 'CFA',
            default => '',
        };
    }

    private function getCourseTypeColor(string $courseType, int $message): string
    {
        if ($message === 1) {
            return match ($courseType) {
                'Bonificado' => '#fde9db',
                'CFA' => '#c5e4e4',
                'Privado' => '#ece1ee',
                'Oferta' => '#eeeeee',
                default => '#eeeeee',
            };
        }

        return match ($courseType) {
            'Bonificado' => '#f8b786',
            'CFA' => '#46b9b0',
            'Privado' => '#c09cc9',
            'Oferta' => '#f0788f',
            default => '#7367F0',
        };
    }

    private function hasRegisteredCourse($item): bool
    {
        return !is_null($item->course_id ?? null) || !is_null($item->course?->id ?? null) || !is_null($item->courseId ?? null);
    }

    private function isPastCalendarDay(string $date): bool
    {
        return Carbon::parse($date)->endOfDay()->lt(now()->startOfDay());
    }

    private function getTaskEventVisual($item, string $date): array
    {
        if ($this->hasRegisteredCourse($item)) {
            return [
                'color' => '#c5e4e4',
                'textColor' => '#1f2937',
            ];
        }

        $done = $this->isPastCalendarDay($date);

        return [
            'color' => $done ? '#c5e4e4' : '#46b9b0',
            'textColor' => $done ? '#1f2937' : '#ffffff',
        ];
    }

    private function serializeTracingForCalendar($tracing): array
    {
        return [
            'id' => (int) $tracing->id,
            'course_id' => (int) ($tracing->course_id ?? $tracing->course?->id ?? 0),
            'student_id' => (int) ($tracing->student_id ?? $tracing->student?->id ?? 0),
            'company_id' => (int) ($tracing->company_id ?? $tracing->company?->id ?? 0),
            'student_name' => $tracing->student_name ?? $tracing->student?->name ?? '',
            'student_surname' => $tracing->student_surname ?? $tracing->student?->surname ?? '',
            'company_name' => $tracing->company_name ?? $tracing->company?->name ?? '',
            'course_name' => $tracing->course_name ?? $tracing->course?->name ?? '',
            'course_type' => $tracing->course_type ?? $tracing->course_type_name ?? $tracing->course?->courseType?->name ?? '',
            'course_status_id' => $tracing->course_status_id ?? $tracing->course?->course_status_id ?? null,
            'course_beginning' => $tracing->course?->beginning ?? null,
            'course_end' => $tracing->course?->end ?? null,
            'follow_up_date' => $tracing->follow_up_date,
            'welcome_date' => $tracing->welcome_date ?? $tracing->course?->welcome_date ?? null,
            'quarter_date' => $tracing->quarter_date ?? $tracing->course?->quarter_date ?? null,
            'half_date' => $tracing->half_date ?? $tracing->course?->half_date ?? null,
            'three_quarters_date' => $tracing->three_quarters_date ?? $tracing->course?->three_quarters_date ?? null,
            'final_date' => $tracing->final_date ?? $tracing->course?->final_date ?? null,
            'welcome_date_sent' => $tracing->welcome_date_sent,
            'quarter_date_sent' => $tracing->quarter_date_sent,
            'half_date_sent' => $tracing->half_date_sent,
            'three_quarters_date_sent' => $tracing->three_quarters_date_sent,
            'final_date_sent' => $tracing->final_date_sent,
        ];
    }

    private function serializeFallbackCourseTracingForCalendar($course, $registration): array
    {
        return [
            'id' => null,
            'course_id' => (int) ($course->id ?? 0),
            'student_id' => (int) ($registration->student_id ?? $registration->student?->id ?? 0),
            'company_id' => (int) ($registration->company_id ?? $registration->company?->id ?? 0),
            'student_name' => $registration->student?->name ?? '',
            'student_surname' => $registration->student?->surname ?? '',
            'company_name' => $registration->company?->name ?? '',
            'course_name' => $course->name ?? '',
            'course_type' => $course->courseType?->name ?? '',
            'course_status_id' => $course->course_status_id ?? null,
            'course_beginning' => $course->beginning ?? null,
            'course_end' => $course->end ?? null,
            'follow_up_date' => null,
            'welcome_date' => $course->welcome_date ?? null,
            'quarter_date' => $course->quarter_date ?? null,
            'half_date' => $course->half_date ?? null,
            'three_quarters_date' => $course->three_quarters_date ?? null,
            'final_date' => $course->final_date ?? null,
            'welcome_date_sent' => null,
            'quarter_date_sent' => null,
            'half_date_sent' => null,
            'three_quarters_date_sent' => null,
            'final_date_sent' => null,
            'is_fallback' => true,
        ];
    }

    private function serializeTrainingElementForCalendar($element): array
    {
        return [
            'id' => (int) $element->id,
            'training_contract_id' => $element->training_contract_id ?? $element->training_contract?->id ?? null,
            'course_id' => $element->course_id ?? null,
            'beginning' => $element->beginning ?? null,
            'end' => $element->end ?? null,
            'course_name' => $element->course?->name ?? $element->certification_name ?? $element->training_action_name ?? '',
            'company_name' => $element->training_contract?->company?->name ?? '',
            'type_label' => $element->course?->courseType?->name ?? 'CFA',
            'training_action_name' => $element->training_action_name ?? '',
            'training_action_total_hours' => $element->training_action_total_hours ?? '',
            'student' => [
                'name' => $element->student_name ?? $element->student?->name ?? $element->training_contract?->student?->name ?? '',
                'surname' => $element->student_surname ?? $element->student?->surname ?? $element->training_contract?->student?->surname ?? '',
            ],
            'training_contract' => [
                'id' => $element->training_contract_id ?? $element->training_contract?->id ?? null,
                'student' => [
                    'name' => $element->training_contract?->student?->name ?? '',
                    'surname' => $element->training_contract?->student?->surname ?? '',
                ],
            ],
        ];
    }

    private function serializeTrainingContractForCalendar($contract): array
    {
        return [
            'id' => (int) $contract->id,
            'beginning' => $contract->beginning ?? null,
            'end' => $contract->end ?? null,
            'beginning_formation' => $contract->beginning_formation ?? null,
            'end_formation' => $contract->end_formation ?? null,
            'formation_hours' => $contract->formation_hours ?? null,
            'formative_hours_first_year' => $contract->formative_hours_first_year ?? null,
            'formative_hours_second_year' => $contract->formative_hours_second_year ?? null,
            'total_hours' => $contract->total_hours ?? null,
            'course_name' => 'Contrato formativo',
            'company_name' => $contract->company_name ?? $contract->company?->name ?? '',
            'type_label' => 'CFA',
            'student' => [
                'name' => $contract->student_name ?? $contract->student?->name ?? '',
                'surname' => $contract->student_surname ?? $contract->student?->surname ?? '',
            ],
            'training_contract' => [
                'id' => (int) $contract->id,
                'student' => [
                    'name' => $contract->student_name ?? $contract->student?->name ?? '',
                    'surname' => $contract->student_surname ?? $contract->student?->surname ?? '',
                ],
            ],
        ];
    }
}

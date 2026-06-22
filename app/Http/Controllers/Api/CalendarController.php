<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\Tracing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends BaseController
{

    /**
     * Obtener los seguimientos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $start = \Illuminate\Support\Carbon::now();
            $number_days = 5;
            if ($start->dayOfWeek >= 2)
                $number_days = 7;
            $start = $start->addDays($number_days);
            $tracings = Tracing::tracing($mainCompanyId);

            $user = User::find(Auth::id());
            if ($user->teacher_id) {
                 $tracings = $tracings->where('courses.teacher_id', $user->teacher_id);
            }

            if ($request->course) {
                $tracings = $tracings->where('courses.id', $request->course);
            }
            if ($request->company) {
                $tracings = $tracings->where('companies.id', $request->company);
            }
            if ($request->student) {
                $tracings = $tracings->where('students.id', 'LIKE', $request->student);
            }
            if ($request->status) {
                $tracings = $tracings
                    ->where('course_statuses.name', 'LIKE', $request->status);
            }
            if ($request->type) {
                $tracings = $tracings
                    ->where('course_types.name', 'LIKE', $request->type);
            }
            if ($request->beginning) {
                $tracings = $tracings->where('courses.beginning', '>=', $request->beginning);
            }
            if ($request->end) {
                $tracings = $tracings->where('courses.beginning', '<=', $request->end);
            }
            $this->applyCalendarDateFilter($tracings, $request);

            if (isset($request['status'])) {
                $courseStatus = CourseStatus::where('name', $request['status'])->first();

                if ($courseStatus) {
                    $tracings->where('course_statuses.id', '!=', $courseStatus->id);
                }
            }

            $tracings = $tracings->orderBy('tracings.id', 'desc')->get();

            $data = [];
            foreach ($tracings as $tracing) {
                $data = [
                    'id' => $tracing->id,

                ];

                // Attempt to get course_status_id from the tracing object
                $data['course_status_id'] = $tracing->course_status_id;

                // If it's still null, try to fetch it directly from the Course model
                if ($tracing['course_status_id'] === null) {
                    $course = Course::find($tracing->course_id);
                    $data['course_status_id'] = $course ? $course->course_status_id : null;
                }
            }

            return $this->sendResponse(
                [
                    'tracings' => $tracings,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            \Log::error("Error in index method: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function applyCalendarDateFilter($query, Request $request): void
    {
        $singleDate = $request->get('calendar_date');
        $from = $this->parseDateFilter($request->get('calendar_date_from') ?? $singleDate);
        $to = $this->parseDateFilter($request->get('calendar_date_to') ?? $singleDate);

        if (!$from && !$to) {
            return;
        }

        if (!$from) {
            $from = $to;
        }
        if (!$to) {
            $to = $from;
        }

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

    private function parseDateFilter($value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}

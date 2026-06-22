<?php

namespace App\Console\Commands;

use App\Helpers\CalculationHelpers;
use App\Helpers\GeneralHelpers;
use App\Helpers\MoodleHelpers;
use App\Models\Course;
use App\Models\Student;
use App\Models\Tracing;
use Carbon\Carbon;
use Illuminate\Console\Command;

class updateCoursesTracings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateCoursesTracings {--months=2 : Include courses finished in the last N months}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update course tracings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $months = max(0, (int) $this->option('months'));
        $now = Carbon::now();
        $recentEndDate = $now->copy()->subMonths($months);

        $courses = Course::with([
                'trainingAction:id,web_platform_id',
                'trainingAction.webPlatform:id,url,token',
            ])
            ->whereDate('beginning', '<=', $now)
            ->whereDate('end', '>=', $recentEndDate)
            ->whereHas('tracings')
            ->whereHas('trainingAction', function ($query) {
                $query->whereNotNull('web_platform_id');
            })
            ->get();

        $this->info(sprintf(
            'Syncing %d courses started up to %s and finished since %s.',
            $courses->count(),
            $now->toDateString(),
            $recentEndDate->toDateString()
        ));

        foreach ($courses as $course) {
            $parts = explode(' - ', $course->name);
            $code = trim($parts[0]);

            $trainingAction = $course->trainingAction;
            if (!$trainingAction || !$trainingAction->web_platform_id) {
                continue;
            }

            $webPlatform = $trainingAction->webPlatform;
            if (!$webPlatform || !$webPlatform->url || !$webPlatform->token) {
                continue;
            }

            $moodleCourse = MoodleHelpers::getCourseByShortname($code.'/'.$course->group, $webPlatform->url, $webPlatform->token);
            if (!$moodleCourse || !isset($moodleCourse['id'])) {
                continue;
            }

            // tracings.course_id points to local courses.id, not Moodle course id
            $tracings = Tracing::where('course_id', $course->id)->get();

            foreach ($tracings as $tracing) {
                $student = Student::find($tracing->student_id);
                if (!$student || empty($student->user)) {
                    continue;
                }

                $courseData = MoodleHelpers::getStudentCourseDetails(
                    $moodleCourse['id'],
                    $student->user,
                    $webPlatform->url,
                    $webPlatform->token
                );

                // Avoid overriding tracing metrics with partial/failed Moodle responses
                if (!empty($courseData['error'])) {
                    continue;
                }

                $endTime = Carbon::createFromTimestamp($tracing->end);
                $currentTime = Carbon::now();

                $tracing->update([
                    'performed_activities' => $courseData['finishedActivities'],
                    'last_connection' => $courseData['lastAccess'] !== 'Never accessed' ? $courseData['lastAccess'] : null,
                    'performed_units' => $courseData['unitsViewed'],
                    'performed_hours' => CalculationHelpers::timeStringToDecimal($courseData['totalTime']),
                    'final_test' => $courseData['evaluationFinalDone'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2),
                    'questionnaire' => $courseData['cuestionar'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2),
                ]);
            }
        }

        $this->info('Moodle data synced successfully.');
    }
}

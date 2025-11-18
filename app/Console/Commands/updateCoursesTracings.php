<?php

namespace App\Console\Commands;

use App\Helpers\CourseStatusHelper;
use App\Helpers\GeneralHelpers;
use App\Helpers\MoodleHelpers;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\Student;
use App\Models\Tracing;
use App\Models\TrainingAction;
use App\Models\WebPlatform;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class updateCoursesTracings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateCoursesTracings';

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
        $courses = Course::whereDate('beginning', '<', Carbon::now())
            ->whereDate('end', '>=', Carbon::now()->subDays(10))
            ->get();

        foreach ($courses as $course) {
            $parts = explode(' - ', $course->name);
            $code = trim($parts[0]);

            $trainingAction = TrainingAction::find($course->training_action_id);

            if ($trainingAction->web_platform_id) {
                $webPlatform = WebPlatform::find($trainingAction->web_platform_id);

                if ($webPlatform->url && $webPlatform->token) {
                    $moodleId = MoodleHelpers::getCourseByShortname($code.'/'.$course->group, $webPlatform->url, $webPlatform->token);
                    if (!$moodleId) continue;

                    $courseId = $moodleId['id'];

                    // Optionally fetch users from your DB that are enrolled in this course
                    $tracings = Tracing::where('course_id', $courseId)->get();

                    foreach ($tracings as $tracing) {
                        $student = Student::where('id', $tracing->student_id)->first();


                        $courseData = MoodleHelpers::getStudentCourseDetails($moodleId['id'], $student->user, $webPlatform->url, $webPlatform->token);

                        $endTime = Carbon::createFromTimestamp($tracing->end);
                        $currentTime = Carbon::now();

                        // Save or update in your DB
                        $tracing->update(
                            [
                                'performed_activities' => $courseData['finishedActivities'],
                                'last_connection' => $courseData['lastAccess'] != 'Never accessed' ? $courseData['lastAccess'] : null,
                                'performed_units' => $courseData['unitsViewed'],
                                'performed_hours' => GeneralHelpers::convertToMinutes($courseData['totalTime']),
                                'final_test' => $courseData['evaluationFinalDone'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2),
                                'questionnaire' => $courseData['cuestionar'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2)
                            ]
                        );

                    }
                }
            }
        }

        $this->info('Moodle data synced successfully.');
    }
}

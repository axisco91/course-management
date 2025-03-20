<?php

namespace App\Console\Commands;

use App\Helpers\CourseStatusHelper;
use App\Helpers\MoodleHelpers;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\Student;
use App\Models\Tracing;
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
    protected $signature = 'updateCoursesStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update course status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $courses = Course::all();

        foreach ($courses as $course){
            $parts = explode(' - ', $course->name);
            $code = trim($parts[0]);

            $moodleCourse = MoodleHelpers::getCourseByShortname($code.'/'.$course->group);

            $tracings = Tracing::where('course_id', $course->id)->get();

            foreach ($tracings as $tracing){
                if (!empty($moodleCourse)) {
                    $courseData = MoodleHelpers::getActivityCount($moodleCourse[0]['id']);
                    $student = Student::find($tracing->student_id);

                    $endTime = Carbon::createFromTimestamp($tracing->end);
                    $currentTime = Carbon::now();

                    $tracing[''] = $courseData['unitsViewed'];
                    $tracing[''] = $courseData['total_time'];
                    $tracing->update([
                        'number_activities' => $courseData['assignmentCount'],
                        'number_units' => $courseData['normalScormCount'],
                        'performed_activities' => $courseData['finishedActivities'],
                        'last_connection' => $courseData['lastAccess'],
                        'performed_units' => $courseData['unitsViewed'],
                        'performed_hours' => $courseData['totalTime'],
                        'final_test' => $courseData['evaluationFinalDone'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2),
                        'questionnaire' => $courseData['cuestionar'] ? 1 : ($endTime->greaterThan($currentTime) ? 0 : 2)
                    ]);
                }
            }
        }

        return 0;
    }
}

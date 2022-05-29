<?php

namespace App\Console\Commands;

use App\Helpers\CourseStatusHelper;
use App\Models\Course;
use Illuminate\Console\Command;

class updateCoursesStatus extends Command
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

            $course_status_id = CourseStatusHelper::updateCourseStatus($course->beggining, $course->end);

            $course->update([
                'course_status_id' => $course_status_id
            ]);
        }

        return 0;
    }
}

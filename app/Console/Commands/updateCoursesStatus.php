<?php

namespace App\Console\Commands;

use App\Helpers\CourseStatusHelper;
use App\Models\Course;
use App\Models\CourseStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
            $anulado = CourseStatus::where('name', 'ANULADO')->first();
            if ($anulado->id != $course->course_status_id) {
                $course_status_id = CourseStatusHelper::updateCourseStatus($course->beginning, $course->end);
            }

            $course->update([
                'course_status_id' => $course_status_id
            ]);
        }

        Mail::getSwiftMailer()
            ->getTransport()
            ->setUsername('zona@avzformacion.com')
            ->setPassword('Avz.2021');

        Mail::raw('', function($message){
            $message->to('franciscohoskins@gmail.com');
            $message->subject('Cron passed course update');
        });
        return 0;
    }
}

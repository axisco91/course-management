<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\CourseStatus;

class CourseStatusHelper
{

    public static function updateCourseStatus($beginning_date, $end_date){
        $now = Carbon::now();
        $beginning = Carbon::createFromFormat('Y-m-d', $beginning_date);
        $end = Carbon::createFromFormat('Y-m-d', $end_date);
        $course_status = CourseStatus::all();
        if ($beginning->gt($now)){
            $course_status_id = $course_status->firstWhere('name', 'PENDIENTE')['id'];
        } else{
            $course_status_id = $course_status->firstWhere('name', 'IMPARTICIÓN')['id'];
        }
        if ($now->gt($end)){
            $course_status_id = $course_status->firstWhere('name', 'FINALIZADO')['id'];
        }
        return $course_status_id;
    }

}

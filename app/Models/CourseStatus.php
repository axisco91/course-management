<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStatus extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonuses()
    {
        return $this->hasMany('App\Models\Bonus', 'course_status_id', 'id');
    }

    public static function getCourseStatuses($keyWord){
        $course_statuses = CourseStatus::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($course_statuses as $course_status){
            $course = Course::where('course_status_id', $course_status['id'])->first();
            if ($course){
                $course_status['used'] = true;
            } else {
                $course_status['used'] = false;
            }
        }
        return $course_statuses;
    }

    public static function createCourseStatus($data){
        $course_status = CourseStatus::create([
            'name' => $data['name']
        ]);

        return $course_status;
    }

    public static function updateCourseStatus($id, $data){
        $course_status = CourseStatus::find($id);
        $course_status->update([
            'name' => $data['name']
        ]);
        return $course_status;
    }

}

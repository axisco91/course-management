<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany('App\Models\Course', 'course_type_id', 'id');
    }

    public static function getCourseTypes($keyWord){
        $course_types = CourseType::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($course_types as $course_type){
            $course = Course::where('course_type_id', $course_type['id'])->first();
            if ($course){
                $course_type['used'] = true;
            } else {
                $course_type['used'] = false;
            }
        }
        return $course_types;
    }

    public static function createCourseType($data){
        $course_type = CourseType::create([
            'name' => $data['name']
        ]);

        return $course_type;
    }

    public static function updateCourseType($id, $data){
        $course_type = CourseType::find($id);
        $course_type->update([
            'name' => $data['name']
        ]);

        return $course_type;
    }
}

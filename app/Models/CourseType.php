<?php

namespace App\Models;

use App\Services\CourseTypeService;
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
        return $this->hasMany(\App\Models\Course::class, 'course_type_id', 'id');
    }

    public function scopeGetCourseType($query)
    {
        return $query
            ->leftJoin('courses', 'courses.course_type_id', '=', 'course_types.id')
            ->select(
                'course_types.*',
                'course_types.id as value',
                'course_types.name as label'
            )
            ->selectRaw('CASE WHEN COUNT(courses.id) > 0 THEN true ELSE false END as used')
            ->groupBy('course_types.id');
    }

    public static function createWithService($data)
    {
        $service = app(CourseTypeService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(CourseTypeService::class);

        return $service->update($this, $data);
    }
}

<?php

namespace App\Models;

use App\Services\CourseStatusService;
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

    /**
     * Services
     */
    public static function createWithService($data)
    {
        $service = app(CourseStatusService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(CourseStatusService::class);

        return $service->update($this, $data);
    }
}

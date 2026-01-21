<?php

namespace App\Models;

use App\Http\Livewire\TrainingUnits;
use App\Services\CourseOriginService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOrigin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'main_company_id'];

    public function scopeGetCourseOrigin($query)
    {
        return $query->select('*', 'id as value', 'name as label');
    }

    /**
     * Services
     */
    public static function createWithService($data)
    {
        $service = app(CourseOriginService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(CourseOriginService::class);

        return $service->update($this, $data);
    }
}

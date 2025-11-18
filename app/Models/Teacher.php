<?php

namespace App\Models;

use App\Services\TeacherService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Teacher extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','surname','dni','email','telephone','user','password','observations','iban','address','post_code','province_id','population', 'active', 'main_company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacherAreas()
    {
        return $this->belongsToMany(TeacherArea::class, 'areas_teacher_areas', 'teacher_id', 'teacher_area_id');
    }

    public function scopeTeacher($query, $mainCompanyId) {
        return $query->select('teachers.*', 'provinces.name as province', 'teachers.id as value',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as label"))
            ->leftjoin('provinces', 'provinces.id', '=', 'teachers.province_id')
            ->where('teachers.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('teachers.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(TeacherService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TeacherService::class);
        return $service->update($this, $data);
    }
}

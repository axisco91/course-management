<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Teacher extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','surname','dni','email','telephone','user','password','observations','iban','address','post_code','province_id','population', 'active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacherAreas()
    {
        return $this->belongsToMany(TeacherArea::class, 'areas_teacher_areas', 'teacher_id', 'teacher_area_id');
    }

    public function scopeTeacher($query) {
        return $query->select('teachers.*', 'provinces.name as province', 'teachers.id as value',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as label"))
            ->leftjoin('provinces', 'provinces.id', '=', 'teachers.province_id');
    }
}

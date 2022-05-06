<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','surname','dni','email','telephone','user','password','observations','iban','address','post_code','province_id','population', 'inactive'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacherAreas()
    {
        return $this->belongsToMany(Teacher::class, 'areas_teacher_areas', 'teacher_id', 'teacher_area_id');
    }
}

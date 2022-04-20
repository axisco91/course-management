<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreasTeacherArea extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['teacher_id','teacher_area_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'id', 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacherArea()
    {
        return $this->hasOne('App\Models\TeacherArea', 'id', 'teacher_area_id');
    }

}

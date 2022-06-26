<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(Teacher::class, 'areas_teacher_areas', 'teacher_id', 'teacher_area_id');
    }

    /**
     * Get all teachers
     */
    public function getTeachers($keyWord, $inactiveFilter, $search_name, $search_surname, $search_email, $search_dni, $search_telephone){
        $teachers = Teacher::select('*')
            ->where(function ($query) use ($keyWord){
            $query->orWhere('name', 'LIKE', $keyWord)
                ->orWhere('surname', 'LIKE', $keyWord)
                ->orWhere('dni', 'LIKE', $keyWord)
                ->orWhere('email', 'LIKE', $keyWord)
                ->orWhere('telephone', 'LIKE', $keyWord)
                ->orWhere('user', 'LIKE', $keyWord)
                ->orWhere('password', 'LIKE', $keyWord)
                ->orWhere('observations', 'LIKE', $keyWord)
                ->orWhere('iban', 'LIKE', $keyWord);
        });
        if ($inactiveFilter != 1) {
            $teachers = $teachers->where('active', 1);
        }
        $teachers = $teachers->where(function ($query) use ($search_name){
            $query->orWhere('teachers.name', 'LIKE', $search_name);
        })->where(function ($query) use ($search_surname){
            $query->orWhere('surname', 'LIKE', $search_surname);
        })->where(function ($query) use ($search_email){
            $query->orWhere('teachers.email', 'LIKE', $search_email);
        })->where(function ($query) use ($search_dni){
            $query->orWhere('dni', 'LIKE', $search_dni);
        })->where(function ($query) use ($search_telephone){
            $query->orWhere('teachers.telephone', 'LIKE', $search_telephone);
        })->orderBy('teachers.name','asc')
            ->paginate(10);

        return $teachers;
    }

    public function findDni($dni, $id = null){
        $teacher = Teacher::where('dni', $dni);
        if ($id){
            $teacher = $teacher->where('id', '!=', $id);
        }
        $teacher = $teacher->first();

        return $teacher;
    }

    public function findUser($user, $id = null){
        $teacher = Teacher::where('user', $user);
        if ($id){
            $teacher = $teacher->where('id', '!=', $id);
        }
        $teacher = $teacher->first();

        return $teacher;
    }
}

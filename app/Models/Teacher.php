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

    /**
     * Get all teachers
     */
    public static function getTeachers(){
        $teachers = Teacher::select('teachers.*', 'provinces.name as province', 'teachers.id as value',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as label"))
            ->leftjoin('provinces', 'provinces.id', '=', 'teachers.province_id')
            ->orderBy('teachers.name','asc')->get();

        foreach ($teachers as $teacher) {
            $course = Course::where('teacher_id', $teacher->id)->first();
            if ($course) {
                $teacher['used'] = true;
            } else {
                $teacher['used'] = false;
            }
            $teacher['teacher_areas'] = $teacher->teacherAreas()->select('id as value', 'name as label')->get()->toArray();
        }
        return $teachers;
    }

    public static function getTeacher($id){
        $teacher = Teacher::select('teachers.*', 'provinces.name as province', 'teachers.id as value',
            DB::raw("CONCAT(teachers.name,' ', teachers.surname) as label"))
            ->leftjoin('provinces', 'provinces.id', '=', 'teachers.province_id')
            ->where('teachers.id', $id)->first();

        $course = Course::where('teacher_id', $teacher->id)
            ->first();
        if ($course) {
            $teacher['used'] = true;
        } else {
            $teacher['used'] = false;
        }
        $teacher['teacher_areas'] = $teacher->teacherAreas()->select('id as value', 'name as label')->get()->toArray();
        return $teacher;
    }

    public static function findDni($dni, $id = null){
        $teacher = Teacher::where('dni', $dni);
        if ($id){
            $teacher = $teacher->where('id', '!=', $id);
        }
        $teacher = $teacher->first();

        return $teacher;
    }

    public static function findUser($user, $id = null){
        $teacher = Teacher::where('user', $user);
        if ($id){
            $teacher = $teacher->where('id', '!=', $id);
        }
        $teacher = $teacher->first();

        return $teacher;
    }

    public function createTeacher($data){
        $teacher = Teacher::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'user' => $data['user'],
            'password' => $data['password'],
            'observations' => $data['observations'],
            'iban' => $data['iban'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
        ]);

        $teacher->update([
            'active' => $data['active']
        ]);

        $teacher->teacherAreas()->sync($data['teacher_areas']);

        return $teacher;
    }

    public function updateTeacher($id, $data){
        $teacher = Teacher::find($id);

        $teacher->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'user' => $data['user'],
            'password' => $data['password'],
            'observations' => $data['observations'],
            'iban' => $data['iban'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
        ]);

        $teacher->update([
            'active' => $data['active']
        ]);

        $teacher->teacherAreas()->sync($data['teacher_areas']);

        return $teacher;
    }
}

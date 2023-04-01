<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherArea extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areasTeacherAreas()
    {
        return $this->hasMany('App\Models\AreasTeacherArea', 'teacher_area_id', 'id');
    }

    public static function getTeacherAreas(){
        $areas = TeacherArea::select('teacher_areas.*', 'id as value', 'name as label')
            ->get();
        foreach ($areas as $area){
            $teacher = AreasTeacherArea::where('teacher_area_id', $area['id'])->first();
            if ($teacher){
                $area['used'] = true;
            } else {
                $area['used'] = false;
            }
        }
        return $areas;
    }

    public static function getTeacherArea($id){
        $area = TeacherArea::select('*', 'id as value', 'name as label')
            ->where('id', $id)->first();
        $teacher = AreasTeacherArea::where('teacher_area_id', $area['id'])->first();
        if ($teacher){
            $area['used'] = true;
        } else {
            $area['used'] = false;
        }
        return $area;
    }


    public static function createTeacherArea($data){
        $teacher_area = TeacherArea::create([
            'name' => $data['name']
        ]);
        $teacher_area = TeacherArea::select('*', 'id as value', 'name as label')
            ->where('id', $teacher_area->id)->first();
        return $teacher_area;
    }

    public static function updateTeacherArea($id, $data){
        $area = TeacherArea::find($id);
        $area->update([
            'name' => $data['name']
        ]);

        return $area;
    }

    public function count(){
        return TeacherArea::count();
    }
}

<?php

namespace App\Models;

use App\Http\Livewire\TrainingUnits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOrigin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public static function getCourseOrigin(){
        $origins = CourseOrigin::select('*', 'id as value', 'name as label')
            ->get();

        foreach ($origins as $origin){
            $training_unit = TrainingUnit::where('course_origin_id', $origin['id'])->first();
            if ($training_unit){
                $origin['used'] = true;
            } else{
                $origin['used'] = false;
            }
        }
        return $origins;
    }

    public static function createCourseOrigin($data){
        $origin = CourseOrigin::create([
            'name' => $data['name']
        ]);

        return $origin;
    }

    public static function updateCourseOrigin($id, $data){
        $origin = CourseOrigin::find($id);
        $origin->update([
            'name' => $data['name']
        ]);

        return $origin;
    }

}

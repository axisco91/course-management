<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoring extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'tutoring_id', 'id');
    }

    public static function getTutorings($keyWord){
        $tutorings = Tutoring::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        return $tutorings;
    }

    public static function createTutoring($data){
        $tutoring = Tutoring::create([
            'name' => $data['name']
        ]);

        return $tutoring;
    }

    public static function updateTutoring($id, $data){
        $tutoring = Tutoring::find($id);
        $tutoring->update([
            'name' => $data['name']
        ]);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelStudy extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $table = 'level_studies';

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'level_study_id', 'id');
    }

    public function getLevelStudies($keyWord){
        $level_studies = LevelStudy::orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($level_studies as $level_study){
            $student = Student::where('level_study_id', $level_study['id'])->first();
            if ($student){
                $level_study['used'] = true;
            } else {
                $level_study['used'] = false;
            }
        }
        return $level_studies;
    }

    public function createLevelStudy($data){
        $level_study = LevelStudy::create([
            'name' => $data['name']
        ]);

        return $level_study;
    }

    public function updateLevelStudy($id, $data){
        $level_study = LevelStudy::find($id);
        $level_study->update([
            'name' => $data['name']
        ]);

        return $level_study;
    }

}

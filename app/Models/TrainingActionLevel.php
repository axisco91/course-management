<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingActionLevel extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $table = 'training_action_levels';

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'training_action_level_id', 'id');
    }

    public function getTrainingActionLevel($keyWord){
        $training_action_levels = TrainingActionLevel::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($training_action_levels as $training_action_level){
            $training_action = TrainingAction::where('training_action_level_id', $training_action_level['id'])->first();
            if ($training_action){
                $training_action_level['used'] = true;
            } else {
                $training_action_level['used'] = false;
            }
        }
        return $training_action_levels;
    }

    public static function createTrainingActionLevel($data){
        $training_action_level = TrainingActionLevel::create([
            'name' => $data['name']
        ]);
        return $training_action_level;
    }

    public static function updateTrainingActionLevel($id, $data){
        $training_action_level = TrainingActionLevel::find($id);
        $training_action_level->update([
            'name' => $data['name']
        ]);
        return $training_action_level;
    }
}

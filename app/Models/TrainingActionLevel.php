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

    public static function getTrainingActionLevels(){
        $trainingAction_levels = TrainingActionLevel::
        select('*', 'id as value', 'name as label')
            ->get();
        foreach ($trainingAction_levels as $trainingAction_level){
            $trainingAction = TrainingAction::where('training_action_level_id', $trainingAction_level['id'])->first();
            if ($trainingAction){
                $trainingAction_level['used'] = true;
            } else {
                $trainingAction_level['used'] = false;
            }
        }
        return $trainingAction_levels;
    }

    public static function getTrainingActionLevel($id){
        $trainingAction_level = TrainingActionLevel::
        select('*', 'id as value', 'name as label')
            ->where('id', $id)->first();
        $trainingAction = TrainingAction::where('training_action_level_id', $trainingAction_level['id'])->first();
        if ($trainingAction){
            $trainingAction_level['used'] = true;
        } else {
            $trainingAction_level['used'] = false;
        }
        return $trainingAction_level;
    }

    public static function createTrainingActionLevel($data){
        $trainingAction_level = TrainingActionLevel::create([
            'name' => $data['name']
        ]);
        return $trainingAction_level;
    }

    public static function updateTrainingActionLevel($id, $data){
        $trainingAction_level = TrainingActionLevel::find($id);
        $trainingAction_level->update([
            'name' => $data['name']
        ]);
        return $trainingAction_level;
    }
}

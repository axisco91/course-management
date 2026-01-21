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

    public function scopeGetTrainingActionLevel($query)
    {
        return $query
            ->select(
                'training_action_levels.*',
                'training_action_levels.id as value',
                'training_action_levels.name as label'
            )
            ->leftJoin(
                'training_actions',
                'training_actions.training_action_level_id',
                '=',
                'training_action_levels.id'
            )
            ->selectRaw('CASE WHEN training_actions.id IS NULL THEN false ELSE true END as used')
            ->groupBy('training_action_levels.id');
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

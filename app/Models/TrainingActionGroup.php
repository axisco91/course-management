<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingActionGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'training_action_group_id', 'id');
    }

    public function scopeGetTrainingActionGroup($query)
    {
        return $query
            ->select(
                'training_action_groups.*',
                'training_action_groups.id as value',
                'training_action_groups.name as label'
            )
            ->leftJoin(
                'training_actions',
                'training_actions.training_action_group_id',
                '=',
                'training_action_groups.id'
            )
            ->selectRaw('CASE WHEN training_actions.id IS NULL THEN false ELSE true END as used')
            ->groupBy('training_action_groups.id');
    }

    public static function createTrainingActionGroup($data){
        $trainingAction_group = TrainingActionGroup::create([
            'name' => $data['name']
        ]);
        return $trainingAction_group;
    }

    public static function updateTrainingActionGroup($id, $data){
        $trainingAction_group = TrainingActionGroup::find($id);
        $trainingAction_group->update([
            'name' => $data['name']
        ]);
        return $trainingAction_group;
    }

}

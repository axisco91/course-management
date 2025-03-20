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

    public static function getTrainingActionGroups(){
        $trainingAction_groups = TrainingActionGroup::
        select('*', 'id as value', 'name as label')
            ->get();
        foreach ($trainingAction_groups as $trainingAction_group){
            $trainingAction = TrainingAction::where('training_action_group_id', $trainingAction_group['id'])->first();
            if ($trainingAction){
                $trainingAction_group['used'] = true;
            } else {
                $trainingAction_group['used'] = false;
            }
        }
        return $trainingAction_groups;
    }

    public static function getTrainingActionGroup($id){
        $group = TrainingActionGroup::select('*', 'id as value', 'name as label')
            ->where('id', $id)->first();
        $trainingAction = TrainingAction::where('training_action_group_id', $group['id'])->first();
        if ($trainingAction){
            $group['used'] = true;
        } else {
            $group['used'] = false;
        }
        return $group;
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

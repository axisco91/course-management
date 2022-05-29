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

    public function getTrainingActionGroups($keyWord){
        $training_action_groups = TrainingActionGroup::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($training_action_groups as $training_action_group){
            $training_action = TrainingAction::where('training_action_group_id', $training_action_group['id'])->first();
            if ($training_action){
                $training_action_group['used'] = true;
            } else {
                $training_action_group['used'] = false;
            }
        }
        return $training_action_groups;
    }

    public function createTrainingActionGroup($data){
        $training_action_group = TrainingActionGroup::create([
            'name' => $data['name']
        ]);
        return $training_action_group;
    }

    public function updateTrainingActionGroup($id, $data){
        $training_action_group = TrainingActionGroup::find($id);
        $training_action_group->update([
            'name' => $data['name']
        ]);
        return $training_action_group;
    }

}

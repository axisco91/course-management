<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'action_type_id', 'id');
    }

    public static function getActionTypes(){
        $actionTypes = ActionType::select('action_types.*', 'id as value', 'name as label')->get();
        foreach ($actionTypes as $actionType){
            $training_action = TrainingAction::where('action_type_id', $actionType['id'])->first();
            if ($training_action){
                $actionType['used'] = true;
            } else{
                $actionType['used'] = false;
            }
        }
        return $actionTypes;
    }

    public static function getActionType($id){
        $actionType = ActionType::select('action_types.*', 'id as value', 'name as label')
            ->where('id', $id)->first();
        $training_action = TrainingAction::where('action_type_id', $actionType['id'])->first();
        if ($training_action){
            $actionType['used'] = true;
        } else{
            $actionType['used'] = false;
        }
        return $actionType;
    }

    public static function createActionType($data){
        $action_type = ActionType::create([
            'name' => $data['name']
        ]);

        return $action_type;
    }

    public static function updateActionType($id, $data){
        $action_type = ActionType::find($id);
        $action_type->update([
            'name' => $data['name']
        ]);
        return $action_type;
    }

}

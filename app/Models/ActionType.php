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

    public function getActionType($keyWord){
        $actionTypes = ActionType::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);

        return $actionTypes;
    }

    public function createActionType($name){
        $action_type = ActionType::create([
            'name' => $name
        ]);

        return $action_type;
    }

    public function updateActionType($id, $name){
        $action_type = ActionType::find($id);
        $action_type->update([
            'name' => $name
        ]);
        return $action_type;
    }

}

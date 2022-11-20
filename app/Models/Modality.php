<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modality extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'modality_id', 'id');
    }

    public static function getModalities($keyWord){
        $modalities = Modality::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($modalities as $modality){
            $training_action = TrainingAction::where('modality_id', $modality['id'])->first();
            if ($training_action){
                $modality['used'] = true;
            } else {
                $modality['used'] = false;
            }
        }
        return $modalities;
    }

    public static function createModality($data){
        $modality = Modality::create([
            'name' => $data['name']
        ]);
        return $modality;
    }

    public static function updateModality($id, $data){
        $modality = Modality::find($id);
        $modality->update([
            'name' => $data['name']
        ]);
        return $modality;
    }

}

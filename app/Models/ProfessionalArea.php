<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalArea extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'professional_area_id', 'id');
    }

    public function getProfessionalAreas($keyWord){
        $professional_areas = ProfessionalArea::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($professional_areas as $professional_area){
            $training_action = TrainingAction::where('professional_area_id', $professional_area['id'])->first();
            if ($training_action){
                $professional_area['used'] = true;
            } else {
                $professional_area['used'] = false;
            }
        }
        return $professional_areas;
    }

    public function createProfessionalAreas($data){
        $professional_area = ProfessionalArea::create([
            'name' => $data['name']
        ]);
        return $professional_area;
    }

    public function updateProfessionalAreas($id, $data){
        $professional_area = ProfessionalArea::find($id);
        $professional_area->update([
            'name' => $data['name']
        ]);
        return $professional_area;
    }

}

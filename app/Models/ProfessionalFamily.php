<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalFamily extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'professional_family_id', 'id');
    }

    public static function getProfessionalFamilies(){
        $professional_families = ProfessionalFamily::
        select('*', 'id as value', 'name as label')
            ->get();
        foreach ($professional_families as $professional_family){
            $training_action = TrainingAction::where('professional_family_id', $professional_family['id']);
            if ($training_action){
                $professional_family['used'] = true;
            } else {
                $professional_family['used'] = false;
            }
        }
        return $professional_families;
    }

    public static function createProfessionalFamily($data){
        $professional_family = ProfessionalFamily::create([
            'name' => $data['name']
        ]);
        return $professional_family;
    }

    public static function updateProfessionalFamily($id, $data){
        $professional_family = ProfessionalFamily::find($id);
        $professional_family->update([
            'name' => $data['name']
        ]);
        return $professional_family;
    }

}

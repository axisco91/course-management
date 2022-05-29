<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAction extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'formative_action','action_type_id','professional_family_id','professional_area_id','modality_id','training_action_level_id','training_action_group_id','tutoring_id','course_z','course_avz','active','in_catalog','face_to_face_hours','teletraining_hours','total_hours','price','objectives','content','user', 'password','web_platform_id','observations','number_activities','number_units','provider_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function actionType()
    {
        return $this->hasOne('App\Models\ActionType', 'id', 'action_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function provider()
    {
        return $this->hasOne('App\Models\Provider', 'id', 'provider_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function modality()
    {
        return $this->hasOne('App\Models\Modality', 'id', 'modality_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function professionalArea()
    {
        return $this->hasOne('App\Models\ProfessionalArea', 'id', 'professional_area_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function professionalFamily()
    {
        return $this->hasOne('App\Models\ProfessionalFamily', 'id', 'professional_family_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trainingActionGroup()
    {
        return $this->hasOne('App\Models\TrainingActionGroup', 'id', 'training_action_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trainingActionLevel()
    {
        return $this->hasOne('App\Models\TrainingActionLevel', 'id', 'training_action_level_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tutoring()
    {
        return $this->hasOne('App\Models\Tutoring', 'id', 'tutoring_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function webPlatform()
    {
        return $this->hasOne('App\Models\WebPlatform', 'id', 'web_platform_id');
    }

    public function createTrainingAction($data){

    }

    public function updateTrainingAction($id, $data){
        $training_action = TrainingAction::find($id);
        $training_action->update([
            'name' => $data['name'],
            'action_type_id' => $data['action_type_id'],
            'professional_family_id' => $data['professional_family_id']  != -1 ? $data['professional_family_id'] : null,
            'professional_area_id' => $data['professional_area_id'] != -1 ? $data['professional_area_id'] : null,
            'modality_id' => $data['modality_id'],
            'training_action_level_id' => $data['training_action_level_id'],
            'training_action_group_id' => $data['training_action_group_id'] != -1 ? $data['training_action_group_id'] : null,
            'tutoring_id' => $data['tutoring_id'],
            'course_z' => $data['course_z'] == true ? 1 : 0,
            'course_avz' => $data['course_avz'] == true ? 1 : 0,
            'active' => $data['active']  == true ? 1 : 0,
            'in_catalog' => $data['in_catalog'] == true ? 1 : 0,
            'face_to_face_hours' => $data['face_to_face_hours'],
            'teletraining_hours' => $data['teletraining_hours'],
            'total_hours' => $data['total_hours'],
            'price' => $data['price'],
            'objectives' => $data['objectives'],
            'content' => $data['content'],
            'user' => $data['user'],
            'password' => $data['password'],
            'web_platform_id' => $data['web_platform_id'],
            'observations' => $data['observations'],
            'number_activities' => $data['number_activities'],
            'number_units' => $data['number_units'],
            'provider_id' => $data['provider_id']
        ]);

        return $training_action;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAction extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','action_type_id','professional_family_id','professional_area_id','modality_id','training_action_level_id','training_action_group_id','tutoring_id','course_z','course_avz','active','in_catalog','face_to_face_hours','teletraining_hours','total_hours','price','objectives','content','user', 'password','web_platform_id','observations','number_activities','number_units','provider_id'];

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

}

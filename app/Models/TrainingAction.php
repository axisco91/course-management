<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAction extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'formative_action','action_type_id','professional_family_id','professional_area_id','modality_id','training_action_level_id','training_action_group_id','tutoring_id','course_z','course_avz','active','in_catalog','face_to_face_hours','teletraining_hours','total_hours','price','objectives','content','user', 'password','web_platform_id','observations','number_activities','number_units','provider_id', 'specialty'];

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

    public function getTrainingActions($keyWord,$inactiveFilter, $search_formative_actions, $search_name, $search_professional_family_id, $search_professional_area_id, $search_modality_id, $search_provider_id, $specialty){
        $trainingActions = TrainingAction::
        select('training_actions.*',
            'action_types.name as action_type', 'professional_families.name as professional_family',
            'professional_areas.name as professional_area', 'modalities.name as modality',
            'training_action_levels.name as training_action_level', 'training_action_groups.name as training_action_group',
            'tutorings.name as tutoring', 'web_platforms.name as web_platform', 'providers.name as provider')
            ->leftjoin('action_types', 'action_types.id', '=', 'training_actions.action_type_id')
            ->leftjoin('professional_families', 'professional_families.id', '=', 'training_actions.professional_family_id')
            ->leftjoin('professional_areas', 'professional_areas.id', '=', 'training_actions.professional_area_id')
            ->leftjoin('modalities', 'modalities.id', '=', 'training_actions.modality_id')
            ->leftjoin('training_action_levels', 'training_action_levels.id', '=', 'training_actions.training_action_level_id')
            ->leftjoin('training_action_groups', 'training_action_groups.id', '=', 'training_actions.training_action_group_id')
            ->leftjoin('tutorings', 'tutorings.id', '=', 'training_actions.tutoring_id')
            ->leftjoin('web_platforms', 'web_platforms.id', '=', 'training_actions.web_platform_id')
            ->leftjoin('providers', 'providers.id', '=', 'training_actions.provider_id');
        if ($inactiveFilter != 1) {
            $trainingActions = $trainingActions->where('training_actions.active', 1);
        }
        if ($specialty == 1) {
            $trainingActions = $trainingActions->where('training_actions.specialty', 1);
        }
        $trainingActions = $trainingActions->where(function ($query) use ($keyWord){
            $query->orWhere('training_actions.name', 'LIKE', $keyWord)
                ->orWhere('action_types.name', 'LIKE', $keyWord)
                ->orWhere('professional_families.name', 'LIKE', $keyWord)
                ->orWhere('professional_areas.name', 'LIKE', $keyWord)
                ->orWhere('modalities.name', 'LIKE', $keyWord)
                ->orWhere('training_action_levels.name', 'LIKE', $keyWord)
                ->orWhere('training_action_groups.name', 'LIKE', $keyWord)
                ->orWhere('tutorings.name', 'LIKE', $keyWord)
                ->orWhere('course_z', 'LIKE', $keyWord)
                ->orWhere('course_avz', 'LIKE', $keyWord)
                ->orWhere('training_actions.active', 'LIKE', $keyWord)
                ->orWhere('in_catalog', 'LIKE', $keyWord)
                ->orWhere('face_to_face_hours', 'LIKE', $keyWord)
                ->orWhere('teletraining_hours', 'LIKE', $keyWord)
                ->orWhere('total_hours', 'LIKE', $keyWord)
                ->orWhere('price', 'LIKE', $keyWord)
                ->orWhere('objectives', 'LIKE', $keyWord)
                ->orWhere('content', 'LIKE', $keyWord)
                ->orWhere('training_actions.user', 'LIKE', $keyWord)
                ->orWhere('web_platforms.name', 'LIKE', $keyWord)
                ->orWhere('training_actions.observations', 'LIKE', $keyWord)
                ->orWhere('number_activities', 'LIKE', $keyWord)
                ->orWhere('number_units', 'LIKE', $keyWord)
                ->orWhere('providers.name', 'LIKE', $keyWord);
        })->where(function ($query) use ($search_formative_actions){
            $query->orWhere('formative_action', 'LIKE', $search_formative_actions);
        })->where(function ($query) use ($search_name){
            $query->orWhere('training_actions.name', 'LIKE', $search_name);
        });
        if ($search_professional_family_id){
            $trainingActions = $trainingActions->where(function ($query) use ($search_professional_family_id){
                $query->orWhere('training_actions.professional_family_id', $search_professional_family_id);
            });
        }
        if ($search_professional_area_id){
            $trainingActions = $trainingActions ->where(function ($query) use ($search_professional_area_id){
                $query->orWhere('training_actions.professional_area_id', $search_professional_area_id);
            });
        }
        if ($search_modality_id){
            $trainingActions = $trainingActions->where(function ($query) use ($search_modality_id){
                $query->orWhere('training_actions.modality_id', $search_modality_id);
            });
        }
       if ($search_provider_id){
           $trainingActions = $trainingActions->where(function ($query) use ($search_provider_id){
               $query->orWhere('training_actions.provider_id', $search_provider_id);
           });
       }

        $trainingActions = $trainingActions->orderby('id', 'asc')
            ->paginate(10);
        return $trainingActions;
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
            'provider_id' => $data['provider_id'],
            'specialty' => $data['specialty'] == true ? 1 : 0,
        ]);

        return $training_action;
    }

    public function getProviderTrainingActions($id, $search_training_actions_name, $search_training_actions_action)
    {
        $training_acions = TrainingAction::where('provider_id', $id)
            ->where(function ($query) use ($search_training_actions_name) {
                $query->orWhere('name', 'LIKE', $search_training_actions_name);
            })->where(function ($query) use ($search_training_actions_action) {
                $query->orWhere('formative_action', 'LIKE', $search_training_actions_action);
            })->get();

        return $training_acions;
    }

    public static function getTrainingActionsNotInModule($module_id){
        $training_actions = TrainingAction::leftjoin('training_actions_modules', 'training_actions_modules.training_action_id', 'training_actions.id')
            ->where('training_actions_modules.module_id', $module_id)->get();
        $not_in_module = TrainingAction::where('active', 1)->get();
        $not_in_module = $not_in_module->whereNotIn('id', $training_actions->pluck('training_actions.id'));
        return $not_in_module;
    }

    public static function getTrainingActionsNotInCertification($certification_id){
        $training_actions = TrainingAction::leftjoin('certification_elements', 'certification_elements.training_action_id', 'training_actions.id')
            ->where('certification_elements.certification_id', $certification_id)->get();
        $not_in_certification = TrainingAction::where('active', 1)->get();
        $not_in_certification = $not_in_certification->whereNotIn('id', $training_actions->pluck('modules.id'));
        return $not_in_certification;
    }

    public static function getSpecialties($id){
        $training_actions = TrainingAction::where('active', 1)
            ->where('specialty', 1)->get();
        return $training_actions;
    }
}

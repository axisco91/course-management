<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainingAction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'formative_action','action_type_id','professional_family_id','professional_area_id','modality_id','training_action_level_id','training_action_group_id','tutoring_id','course_z','course_avz','active','in_catalog','face_to_face_hours','teletraining_hours','total_hours','price','objectives','content','user', 'password','web_platform_id','observations','number_activities','number_units','provider_id', 'specialty', 'course_origin_id'];

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

    public function scopeActive($query) {
        return $query->select('id as value', DB::raw("CONCAT(formative_action,' - ',name) as label"))
            ->where('active', 1);
    }

    public function scopeTrainingAction($query) {
        return $query->select('training_actions.*',
            'action_types.name as action_type',
            'professional_families.name as professional_family',
            'professional_areas.name as professional_area',
            'modalities.name as modality',
            'training_action_levels.name as training_action_level',
            'training_action_groups.name as training_action_group',
            'tutorings.name as tutoring',
            'web_platforms.name as web_platform',
            'providers.name as provider',
            DB::raw('(CASE
                        WHEN in_catalog = "0" THEN "No"
                        WHEN in_catalog = "1" THEN "Si"
                        END) AS in_catalog'),
            DB::raw('(CASE
                        WHEN training_actions.active = "0" THEN "Inactivo"
                        WHEN training_actions.active = "1" THEN "Activo"
                        END) AS status'))
            ->leftjoin('action_types', 'action_types.id', '=', 'training_actions.action_type_id')
            ->leftjoin('professional_families', 'professional_families.id', '=', 'training_actions.professional_family_id')
            ->leftjoin('professional_areas', 'professional_areas.id', '=', 'training_actions.professional_area_id')
            ->leftjoin('modalities', 'modalities.id', '=', 'training_actions.modality_id')
            ->leftjoin('training_action_levels', 'training_action_levels.id', '=', 'training_actions.training_action_level_id')
            ->leftjoin('training_action_groups', 'training_action_groups.id', '=', 'training_actions.training_action_group_id')
            ->leftjoin('tutorings', 'tutorings.id', '=', 'training_actions.tutoring_id')
            ->leftjoin('web_platforms', 'web_platforms.id', '=', 'training_actions.web_platform_id')
            ->leftjoin('providers', 'providers.id', '=', 'training_actions.provider_id');
    }

    public static function getProviderTrainingActions($id)
    {
        $training_acions = TrainingAction::where('provider_id', $id)->get();

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
        $training_contracts_specialties = TrainingContractElement::where('training_contract_elements.training_contract_id', $id)
            ->whereNotNull('training_action_id')
            ->pluck('training_action_id');
        $training_actions = TrainingAction::select('training_actions.*', 'training_actions.id as value', DB::raw("CONCAT(training_actions.name,' (', training_actions.total_hours,' horas)') as label"))
            ->where('active', 1)
            ->where('specialty', 1)
            ->whereNotIn('id', $training_contracts_specialties)->get();
        return $training_actions;
    }
}

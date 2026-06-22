<?php

namespace App\Models;

use App\Services\TrainingActionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainingAction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'formative_action','action_type_id','professional_family_id','professional_area_id','modality_id','training_action_level_id','training_action_group_id','tutoring_id','course_z','course_avz','active','in_catalog','face_to_face_hours','teletraining_hours','total_hours','price','objectives','content','user', 'password','web_platform_id','observations','number_activities','number_units','provider_id', 'specialty', 'course_origin_id', 'code', 'training_tutor', 'training_tutor_dni', 'main_company_id'];

    public function actionType()
    {
        return $this->belongsTo(ActionType::class, 'action_type_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function courseOrigin()
    {
        return $this->belongsTo(CourseOrigin::class, 'course_origin_id');
    }

    public function modality()
    {
        return $this->belongsTo(Modality::class, 'modality_id');
    }

    public function professionalArea()
    {
        return $this->belongsTo(ProfessionalArea::class, 'professional_area_id');
    }

    public function professionalFamily()
    {
        return $this->belongsTo(ProfessionalFamily::class, 'professional_family_id');
    }

    public function trainingActionGroup()
    {
        return $this->belongsTo(TrainingActionGroup::class, 'training_action_group_id');
    }

    public function trainingActionLevel()
    {
        return $this->belongsTo(TrainingActionLevel::class, 'training_action_level_id');
    }

    public function tutoring()
    {
        return $this->belongsTo(Tutoring::class, 'tutoring_id');
    }

    public function webPlatform()
    {
        return $this->belongsTo(WebPlatform::class, 'web_platform_id');
    }

    public function scopeActive($query, int $mainCompanyId)
    {
        return $query
            ->select('id as value', DB::raw("CONCAT(formative_action,' - ',name) as label"))
            ->where('active', 1)
            ->where('main_company_id', $mainCompanyId);
    }


    public function scopeTrainingAction($query, int $mainCompanyId)
    {
        return $query
            ->where('training_actions.main_company_id', $mainCompanyId)
            ->with([
                'actionType:id,name',
                'professionalFamily:id,name',
                'professionalArea:id,name',
                'modality:id,name',
                'trainingActionLevel:id,name',
                'trainingActionGroup:id,name',
                'tutoring:id,name',
                'webPlatform:id,name',
                'provider:id,name',
                'courseOrigin:id,name',
            ]);
    }

    public function scopeGetProviderTrainingActions($query, $providerId, $mainCompanyId)
    {
        return $query
            ->where('provider_id', $providerId)
            ->where('main_company_id', $mainCompanyId);
    }


    public function scopeGetTrainingActionsNotInModule($query, $moduleId, $mainCompanyId)
    {
        return $query
            ->where('training_actions.active', 1)
            ->where('training_actions.main_company_id', $mainCompanyId)
            ->whereNotIn('training_actions.id', function ($sub) use ($moduleId) {
                $sub->select('training_actions_modules.training_action_id')
                    ->from('training_actions_modules')
                    ->where('training_actions_modules.module_id', $moduleId);
            });
    }

    public function scopeGetTrainingActionsNotInCertification($query, $certificationId, $mainCompanyId)
    {
        return $query
            ->where('training_actions.active', 1)
            ->where('training_actions.main_company_id', $mainCompanyId)
            ->whereNotIn('training_actions.id', function ($sub) use ($certificationId) {
                $sub->select('certification_elements.training_action_id')
                    ->from('certification_elements')
                    ->where('certification_elements.certification_id', $certificationId);
            });
    }

    public function scopeGetSpecialties($query, $trainingContractId, $mainCompanyId)
    {
        return $query
            ->select(
                'training_actions.*',
                'training_actions.id as value',
                DB::raw("CONCAT(training_actions.name,' (', training_actions.total_hours,' horas)') as label")
            )
            ->where('training_actions.active', 1)
            ->where('training_actions.specialty', 1)
            ->where('training_actions.main_company_id', $mainCompanyId)
            ->whereNotIn('training_actions.id', function ($sub) use ($trainingContractId, $mainCompanyId) {
                $sub->select('training_contract_elements.training_action_id')
                    ->from('training_contract_elements')
                    ->where('training_contract_elements.training_contract_id', $trainingContractId)
                    ->whereNotNull('training_contract_elements.training_action_id')
                    ->where('training_contract_elements.main_company_id', $mainCompanyId);
            });
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('training_actions.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(TrainingActionService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TrainingActionService::class);
        return $service->update($this, $data);
    }
}

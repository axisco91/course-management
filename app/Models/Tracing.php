<?php

namespace App\Models;

use App\Services\TracingService;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tracing extends Model
{
    use HasFactory;

    private const EXCLUDED_TRAINING_CONTRACT_STATUSES = ['BAJA', 'BAJA IT', 'NO FORMALIZADO'];

    public $timestamps = true;

    protected $fillable = ['course_id','company_id','student_id','performed_activities','performed_hours','performed_units','follow_up_date','suitability','final_test','questionnaire','welcome_message','quarter_message','half_message','three_quarters_message','final_message','one_week_message','observation', 'welcome_date_sent', 'quarter_date_sent', 'half_date_sent', 'three_quarters_date_sent', 'final_date_sent', 'one_week_date_sent', 'last_connection', 'training_contract_element_id', 'main_company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course()
    {
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration', 'tracing_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function training_contract_element()
    {
        return $this->belongsTo(TrainingContractElement::class, 'training_contract_element_id');
    }

    // Tracing model
    public function scopeTracing($query, $mainCompanyId)
    {
        return $query
            ->select('tracings.*')
            ->with([
                'company:id,name',
                'student:id,name,surname',
                'training_contract_element.training_contract.trainingContractStatus:id,name',
                'course' => function ($q) {
                    $q->select(
                        'id',
                        'name',
                        'training_action_id',
                        'teacher_id',
                        'course_status_id',
                        'course_type_id',
                        'group',
                        'beginning',
                        'end',
                        'welcome_date',
                        'quarter_date',
                        'half_date',
                        'three_quarters_date',
                        'final_date'
                    )->with([
                        'trainingAction:id,formative_action,name,number_activities,number_units,total_hours',
                        'teacher:id,name,surname,user',
                        'courseStatus:id,name',
                        'courseType:id,name',
                    ]);
                },
            ])
            ->where('tracings.main_company_id', $mainCompanyId)
            ->where(function ($q) {
                $q->whereNull('tracings.training_contract_element_id')
                    ->orWhereDoesntHave('training_contract_element.training_contract', function ($contractQuery) {
                        $contractQuery
                            ->leftJoin('training_contract_statuses', 'training_contract_statuses.id', '=', 'training_contracts.training_contract_status_id')
                            ->whereIn(DB::raw('UPPER(training_contract_statuses.name)'), self::EXCLUDED_TRAINING_CONTRACT_STATUSES);
                    });
            })
            ->addSelect([
                DB::raw("
                    CASE
                        WHEN tracings.final_test = 0 THEN 'Pendiente'
                        WHEN tracings.final_test = 1 THEN 'Realizado'
                        WHEN tracings.final_test = 2 THEN 'No realizado'
                        ELSE NULL
                    END AS final_test_name
                "),
                DB::raw("
                    CASE
                        WHEN tracings.questionnaire = 0 THEN 'Pendiente'
                        WHEN tracings.questionnaire = 1 THEN 'Realizado'
                        WHEN tracings.questionnaire = 2 THEN 'No realizado'
                        ELSE NULL
                    END AS questionnaire_name
                "),
            ]);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('tracings.main_company_id', $mainCompanyId);
    }

    public static function createWithService($data)
    {
        $service = app(TracingService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(TracingService::class);
        return $service->update($this, $data);
    }
}

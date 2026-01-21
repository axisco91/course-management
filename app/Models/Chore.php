<?php

namespace App\Models;

use App\Services\ChoreService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chore extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'course_id',
        'company_id',
        'student_id',
        'membership_tab_status',
        'membership_tab_date',
        'economic_proposal_status',
        'economic_proposal_date',
        'student_tab_status',
        'student_tab_date',
        'welcome_guid_status',
        'welcome_guid_date',
        'registration_status',
        'registration_date',
        'diploma_status',
        'diploma_status_date',
        'start_communication_status',
        'start_communication_date',
        'close_communication_status',
        'close_communication_date',
        'invoiced_status',
        'invoiced_date',
        'bonus_sent_status',
        'bonus_sent_date',
        'quarter_date_sent',
        'half_date_sent',
        'three_quarters_date_sent',
        'final_date_sent',
        'training_contract_element_id',
        'send_doc_status',
        'send_doc_date',
        'tutor_guide_status',
        'tutor_guide_date',
        'main_company_id',
    ];

    protected $appends = [
        'membership_tab_status_name',
        'economic_proposal_status_name',
        'student_tab_status_name',
        'welcome_guid_status_name',
        'registration_status_name',
        'diploma_status_name',
        'start_communication_status_name',
        'close_communication_status_name',
        'invoiced_status_name',
        'bonus_sent_status_name',
        'send_doc_status_name',
        'tutor_guide_status_name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'chore_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeFilterMainCompany($query, $mainCompanyId)
    {
        return $query->where('chores.main_company_id', $mainCompanyId);
    }

    // Lista general de chores con todos los joins/labels
    public function scopeChore($query, $mainCompanyId)
    {
        return $query
            ->select('chores.*')
            ->with([
                'company:id,name',
                'student:id,name,surname',
                'course:id,group,beginning,end,teacher_id,training_action_id,course_status_id,course_type_id',
                'course.trainingAction:id,formative_action,name',
                'course.courseStatus:id,name',
                'course.courseType:id,name',
            ])
            ->filterMainCompany($mainCompanyId);
    }

    /**
     * Scope equivalente a getChoresSendWelcome($mainCompanyId)
     * (chores con welcome_guid pendiente)
     */
    public function scopeChoresSendWelcome($query, int $mainCompanyId)
    {
        return $query
            ->select(
                'chores.*',
                'courses.name as course',
                'companies.name as company',
                'students.name as student_name',
                'students.surname as student_surname',
                'courses.beginning',
                'course_statuses.name as status',
                'courses.group as course_group',
                DB::raw("CONCAT(students.name,' ', students.surname) as student")
            )
            ->leftJoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftJoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftJoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftJoin('students', 'students.id', '=', 'chores.student_id')
            ->where('welcome_guid_status', 0)
            ->FilterMainCompany($mainCompanyId);
    }

    public function getMembershipTabStatusNameAttribute()
    {
        return match ($this->membership_tab_status) {
            0 => 'Pendiente',
            1 => 'Enviada',
            2 => 'Recibida',
            3 => 'No procede',
            default => null,
        };
    }

    public function getEconomicProposalStatusNameAttribute()
    {
        return match ($this->economic_proposal_status) {
            0 => 'Pendiente',
            1 => 'Enviada',
            2 => 'Recibida',
            default => null,
        };
    }

    public function getStudentTabStatusNameAttribute()
    {
        return match ($this->student_tab_status) {
            0 => 'Pendiente',
            1 => 'Enviada',
            2 => 'Recibida',
            default => null,
        };
    }

    public function getWelcomeGuidStatusNameAttribute()
    {
        return match ($this->welcome_guid_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            default => null,
        };
    }

    public function getRegistrationStatusNameAttribute()
    {
        return match ($this->registration_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            default => null,
        };
    }

    public function getDiplomaStatusNameAttribute()
    {
        return match ($this->diploma_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            2 => 'No procede',
            default => null,
        };
    }

    public function getStartCommunicationStatusNameAttribute()
    {
        return match ($this->start_communication_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            2 => 'No procede',
            default => null,
        };
    }

    public function getCloseCommunicationStatusNameAttribute()
    {
        return match ($this->close_communication_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            2 => 'No procede',
            default => null,
        };
    }

    public function getInvoicedStatusNameAttribute()
    {
        return match ($this->invoiced_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            2 => 'No procede',
            default => null,
        };
    }

    public function getBonusSentStatusNameAttribute()
    {
        return match ($this->bonus_sent_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            2 => 'No procede',
            default => null,
        };
    }

    public function getSendDocStatusNameAttribute()
    {
        return match ($this->send_doc_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            2 => 'No procede',
            default => null,
        };
    }

    public function getTutorGuideStatusNameAttribute()
    {
        return match ($this->tutor_guide_status) {
            0 => 'Pendiente',
            1 => 'Realizada',
            default => null,
        };
    }

    public static function createWithService($data)
    {
        $service = app(ChoreService::class);

        return $service->create($data);
    }

    public function updateWithService($data)
    {
        $service = app(ChoreService::class);

        return $service->update($this, $data);
    }

    public function updateCommunicationStartDate($data)
    {
        $service = app(ChoreService::class);

        return $service->updateCommunicationStartDate($this, $data);
    }

    public function updateCommunicationEndDate($data)
    {
        $service = app(ChoreService::class);

        return $service->updateCommunicationEndDate($this, $data);
    }
}

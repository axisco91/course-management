<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chore extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id',
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
        'training_contract_element_id'];

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
        return $this->hasMany('App\Models\Registration', 'chore_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function scopeChore($query) {
        return $query->select('chores.*', DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'companies.name as company', 'students.name as student_name',
            'students.surname as student_surname', 'course_statuses.name as status', 'courses.group as course_group',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            'courses.beginning as beginning',
            'courses.end as end')
            ->leftjoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftjoin('students', 'students.id', '=', 'chores.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');
    }

    public static function getChoresSendWelcome(){
        $chores = Chore::select('chores.*', 'courses.name as course', 'companies.name as company', 'students.name as student_name',
            'courses.beginning',
            'students.surname as student_surname', 'course_statuses.name as status', 'courses.group as course_group')
            ->leftjoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftjoin('students', 'students.id', '=', 'chores.student_id')
            ->where('welcome_guid_status', 0)
            ->get();

        foreach ($chores as $chore){
            $chore['student'] = $chore['student_name'].' '.$chore['student_surname'];
        }

        return $chores;
    }

    public static function billingDateChore($id, $date, $status){
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration){
            $chore = Chore::find($registration->chore_id);
            $chore->update([
                'bonus_sent_status' => $status,
                'bonus_sent_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null,
                'invoiced_status' => $status,
                'invoiced_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null
                ]);
        }
        return true;
    }
}

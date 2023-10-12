<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tracing extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id','company_id','student_id','performed_activities','performed_hours','performed_units','follow_up_date','final_test','questionnaire','welcome_message','quarter_message','half_message','three_quarters_message','final_message','observation', 'welcome_date_sent', 'quarter_date_sent', 'half_date_sent', 'three_quarters_date_sent', 'final_date_sent', 'last_connection', 'training_contract_element_id'];

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

    public function scopeTracing($query) {
        return $query->select('tracings.*',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'companies.name as company',
            'students.name as student_name',
            'students.surname as student_surname',
            'training_actions.number_activities',
            'training_actions.number_units',
            'training_actions.total_hours',
            'course_statuses.name as status',
            'courses.group as course_group',
            'courses.welcome_date',
            'courses.quarter_date',
            'courses.half_date',
            'courses.three_quarters_date',
            'courses.final_date',
            'course_types.name as course_type',
            DB::raw("CONCAT(students.name,' ',students.surname) as student"))
            ->leftjoin('courses', 'courses.id', '=', 'tracings.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'tracings.company_id')
            ->leftjoin('students', 'students.id', '=', 'tracings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('course_types', 'course_types.id', '=', 'courses.course_type_id');
    }
}

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

    protected $fillable = ['course_id','company_id','student_id','performed_activities','performed_hours','performed_units','follow_up_date','final_test','questionnaire','welcome_message','quarter_message','half_message','three_quarters_message','final_message','observation', 'welcome_date_sent', 'quarter_date_sent', 'half_date_sent', 'three_quarters_date_sent', 'final_date_sent', 'last_connection'];

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

    public static function getTracings(){
        $start = Carbon::now();
        $number_days = 5;
        if ($start->dayOfWeek >= 2)
            $number_days = 7;
        $start = $start->addDays($number_days);
        $tracings = Tracing::select('tracings.*',
            'courses.name as course',
            'companies.name as company',
            'students.name as student_name',
            'students.surname as student_surname',
            'training_actions.number_activities',
            'training_actions.number_units',
            'training_actions.total_hours',
            'course_statuses.name as status',
            'courses.group as course_group',
            DB::raw("CONCAT(students.name,' ',students.surname) as student"))
            ->leftjoin('courses', 'courses.id', '=', 'tracings.course_id')
            ->leftjoin('course_statuses', 'course_statuses.id', 'courses.course_status_id')
            ->leftjoin('companies', 'companies.id', '=', 'tracings.company_id')
            ->leftjoin('students', 'students.id', '=', 'tracings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->orderBy('tracings.id', 'desc')->get();

        foreach ($tracings as $tracing){
            if ($tracing->final_test === 0) {
                $tracing['final_test_name'] = 'Pendiente';
            } else if ($tracing->final_test === 1) {
                $tracing['final_test_name'] = 'Realizado';
            } else if ($tracing->final_test === 2) {
                $tracing['final_test_name'] = 'No realizado';
            }
            if ($tracing->questionnaire === 0) {
                $tracing['questionnaire_name'] = 'Pendiente';
            } else if ($tracing->questionnaire === 1) {
                $tracing['questionnaire_name'] = 'Realizado';
            } else if ($tracing->questionnaire === 2) {
                $tracing['questionnaire_name'] = 'No realizado';
            }
        }
        return $tracings;
    }

    public static function createTracing($data){
        $tracing = Tracing::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
        ]);
        return $tracing;
    }

    public static function updateTracing($id, $data){
        $tracing = Tracing::find($id);
        $tracing->update([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'last_connection' => $data['last_connection'] ? Carbon::createFromFormat('d-m-Y', $data['last_connection'])->format('Y-m-d') : null,
            'performed_activities' => $data['performed_activities'],
            'performed_hours' => $data['performed_hours'],
            'performed_units' => $data['performed_units'],
            'follow_up_date' => $data['follow_up_date'] ? Carbon::createFromFormat('d-m-Y', $data['follow_up_date'])->format('Y-m-d') : null,
            'final_test' => $data['final_test'],
            'questionnaire' => $data['questionnaire'],
            'observation' => $data['observation'],
            'welcome_date_sent' => $data['welcome_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['welcome_date_sent'])->format('Y-m-d') : null,
            'quarter_date_sent' => $data['quarter_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['quarter_date_sent'])->format('Y-m-d') : null,
            'half_date_sent' => $data['half_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['half_date_sent'])->format('Y-m-d') : null,
            'three_quarters_date_sent' => $data['three_quarters_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['three_quarters_date_sent'])->format('Y-m-d') : null,
            'final_date_sent' => $data['final_date_sent'] ? Carbon::createFromFormat('d-m-Y', $data['final_date_sent'])->format('Y-m-d') : null,
            'welcome_message' => $data['welcome_message'],
            'quarter_message' => $data['quarter_message'],
            'half_message' => $data['half_message'],
            'three_quarters_message' => $data['three_quarters_message'],
            'final_message' => $data['final_message']
        ]);
        return $tracing;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracing extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id','company_id','student_id','performed_activities','performed_hours','performed_units','follow_up_date','final_test','questionnaire','welcome_message','quarter_message','half_message','three_quarters_message','final_message','observation', 'welcome_date_sent', 'quarter_date_sent', 'half_date_sent', 'three_quarters_date_sent', 'final_date_sent'];

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

    public function getTracings($keyWord, $course_search, $company_search, $student_search){
        $tracings = Tracing::select('tracings.*', 'courses.name as course', 'companies.name as company', 'students.name as student_name', 'students.surname as student_surname',
            'training_actions.number_activities', 'training_actions.number_units', 'training_actions.total_hours')
            ->leftjoin('courses', 'courses.id', '=', 'tracings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'tracings.company_id')
            ->leftjoin('students', 'students.id', '=', 'tracings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');

        if ($course_search != -1){
            $tracings = $tracings->where('courses.id', $course_search);
        }
        if ($company_search != -1){
            $tracings = $tracings->where('companies.id', $company_search);
        }
        if ($student_search != -1){
            $tracings = $tracings->where('students.id', 'LIKE', $student_search);
        }
        $tracings = $tracings->where(function ($query) use ($keyWord) {
            $query->orWhere('performed_activities', 'LIKE', $keyWord)
                ->orWhere('performed_hours', 'LIKE', $keyWord)
                ->orWhere('performed_units', 'LIKE', $keyWord)
                ->orWhere('follow_up_date', 'LIKE', $keyWord)
                ->orWhere('final_test', 'LIKE', $keyWord)
                ->orWhere('questionnaire', 'LIKE', $keyWord)
                ->orWhere('welcome_message', 'LIKE', $keyWord)
                ->orWhere('quarter_message', 'LIKE', $keyWord)
                ->orWhere('half_message', 'LIKE', $keyWord)
                ->orWhere('three_quarters_message', 'LIKE', $keyWord)
                ->orWhere('final_message', 'LIKE', $keyWord)
                ->orWhere('tracings.observation', 'LIKE', $keyWord);
        })->orderBy('courses.beginning', 'desc')->paginate(10);
        return $tracings;
    }

    public function createTracing($data){
        $tracing = Tracing::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
        ]);
        return $tracing;
    }

    public function updateTracing($id, $data){
        $tracing = Tracing::find($id);
        $tracing->update([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'performed_activities' => $data['performed_activities'],
            'performed_hours' => $data['performed_hours'],
            'performed_units' => $data['performed_units'],
            'follow_up_date' => $data['follow_up_date'],
            'final_test' => $data['final_test'],
            'questionnaire' => $data['questionnaire'],
            'welcome_message' => $data['welcome_message'],
            'quarter_message' => $data['quarter_message'],
            'half_message' => $data['half_message'],
            'three_quarters_message' => $data['three_quarters_message'],
            'final_message' => $data['final_message'],
            'observation' => $data['observation'],
            'welcome_date_sent' => $data['welcome_date_sent'],
            'quarter_date_sent' => $data['quarter_date_sent'],
            'half_date_sent' => $data['half_date_sent'],
            'three_quarter_date_sent' => $data['three_quarters_date_sent'],
            'final_date_sent' => $data['final_date_sent']
        ]);
        return $tracing;
    }

}

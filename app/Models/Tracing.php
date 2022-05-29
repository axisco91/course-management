<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracing extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id','company_id','student_id','performed_activities','performed_hours','performed_units','follow_up_date','final_test','questionnaire','welcome_message','quarter_message','half_message','three_quarters_message','final_message','observation'];

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

    public function getTracings($keyWord){
        $tracings = Tracing::select('tracings.*', 'courses.name as course', 'companies.name as company', 'students.name as student')
            ->leftjoin('courses', 'courses.id', '=', 'tracings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'tracings.company_id')
            ->leftjoin('students', 'students.id', '=', 'tracings.student_id');

        if ($this->course_search != -1){
            $tracings = $tracings->where('courses.id', $this->course_search);
        }
        if ($this->company_search != -1){
            $tracings = $tracings->where('companies.id', $this->company_search);
        }
        if ($this->student_search != -1){
            $tracings = $tracings->where('students.id', 'LIKE', $this->student_search);
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
        })->paginate(10);
        return $tracings;
    }

    public function createTracing($data){
        $tracing = Tracing::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['id'],
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
            'observation' => $data['observation']
        ]);
        return $tracing;
    }

}

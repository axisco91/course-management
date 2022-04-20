<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['name','training_action_id','group','course_type_id','teacher_id','nebrija','beginning','end','morning_schedule','afternoon_schedule','monday','tuesday','wednesday','thursday','friday','saturday','sunday','formation_center_id','delivery_center_id','outsourced','course_observation','reactivated','welcome_date','quater_date','half_date','three_quarters_date','final_date','course_status_id','price'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonuses()
    {
        return $this->hasMany('App\Models\Bonus', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function center()
    {
        return $this->hasOne('App\Models\Center', 'id', 'formation_center_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function courseStatuses()
    {
        return $this->hasOne('App\Models\CourseStatus', 'id', 'course_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryCenter()
    {
        return $this->hasOne('App\Models\Center', 'id', 'delivery_center_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chores()
    {
        return $this->hasMany('App\Models\Chore', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function courseType()
    {
        return $this->hasOne('App\Models\CourseType', 'id', 'course_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'id', 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracings()
    {
        return $this->hasMany('App\Models\Tracing', 'course_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trainingAction()
    {
        return $this->hasOne('App\Models\TrainingAction', 'id', 'training_action_id');
    }

    public function messageDates($beggining, $end){

        $beggining = Carbon::createFromFormat('Y-m-d', $beggining);
        $end = Carbon::createFromFormat('Y-m-d', $end);

        $difrence = $beggining->diffInDays($end);
        $quater_days = ($difrence/2)/2;

        $quater = Carbon::createFromFormat('Y-m-d', $beggining->toDateString());

        $quater = $quater->addDays($quater_days);
        $half = Carbon::createFromFormat('Y-m-d', $beggining->toDateString());

        $half = $half->addDays($difrence/2);
        $three_quaters = Carbon::createFromFormat('Y-m-d', $end->toDateString());

        $three_quaters = $three_quaters->subDays($quater_days);

        return [
            'quater' => $quater->toDateString(),
            'half' => $half->toDateString(),
            'three_quaters' => $three_quaters->toDateString()
        ];
    }

}

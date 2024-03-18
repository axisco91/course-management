<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Registration extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id',
        'company_id',
        'student_id',
        'tracing_id',
        'chore_id',
        'price',
        'profitability_id',
        'is_bonus',
        'billing_id',
        'status',
        'on_leave_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chore()
    {
        return $this->hasOne('App\Models\Chore', 'id', 'chore_id');
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tracing()
    {
        return $this->hasOne('App\Models\Tracing', 'id', 'tracing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profitability()
    {
        return $this->hasOne('App\Models\Profitability', 'id', 'profitability_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billing()
    {
        return $this->hasOne('App\Models\Bill', 'id', 'billing_id');
    }

    public static function totalRegistrations(){
        $now = Carbon::now();
        $total = Registration::leftJoin('courses', 'registrations.course_id', '=', 'courses.id')->where('courses.beginning', '>=', $now->year.'-01-01')
            ->where('courses.beginning', '<=', $now->year.'-12-31')->get();
        return $total->count();
    }

    public static function countRegistrations($start, $limit){
        $registrations = Registration::leftJoin('courses', 'registrations.course_id', '=', 'courses.id')->where('courses.beginning', '>=', $start)
            ->where('courses.beginning', '<=', $limit)->get();

        return $registrations->count();
    }

    public function scopeStudentCourses($query, $id) {
        return $query->select('courses.*')->leftJoin('courses', 'registrations.course_id', '=', 'courses.id')
            ->where('registrations.student_id', $id);
    }

    public static function eliminateBill($id){
        $registrations = Registration::where('billing_id', $id)->get();
        if ($registrations){
            foreach($registrations as $registration){
                $registration->update([
                    'billing_id' => null
                ]);
            }
        }
        return true;
    }

    public function scopeBillingRegistration($query, $billId) {
        return $query->where('billing_id', $billId);
    }
}

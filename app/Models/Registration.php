<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'billing_id'];

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
        return $this->hasOne('App\Models\billing', 'id', 'billing_id');
    }

    public function getRegistrated($course_id, $search_name, $search_surname){
        $registations = Student::select('students.*')->leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
            ->where('registrations.course_id', $course_id)
            ->where(function ($query) use ($search_name){
                $query->orWhere('name', 'LIKE', $search_name);
            }) ->where(function ($query) use ($search_surname){
                $query->orWhere('surname', 'LIKE', $search_surname);
            })->get();

        return $registations;
    }

    public function getUnregistrated($course_id, $search_name_unregisterd, $search_surname_unregisterd){
        $registations = Student::leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
            ->where('registrations.course_id', '=', $course_id)->get();
        $unregisted = Student::where(function ($query) use ($search_name_unregisterd){
                $query->orWhere('name', 'LIKE', $search_name_unregisterd);
            }) ->where(function ($query) use ($search_surname_unregisterd){
                $query->orWhere('surname', 'LIKE', $search_surname_unregisterd);
            })->get();

        $unregisted = $unregisted->whereNotIn('id', $registations->pluck('student_id'));

        return $unregisted;
    }

    public function createRegistration($data){
        $registration = Registration::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id'],
            'billing_id' => $data['billing_id'],
            'tracing_id' => $data['tracing_id'],
            'chore_id' => $data['chore_id'],
            'price' => $data['price'],
            'profitability_id' => $data['profitability_id'],
            'is_bonus' => $data['is_bonus']
        ]);
        return $registration;
    }

    public function unregistration($id){
        $registration = Registration::where('course_id', $this->selected_id)
            ->where('student_id', $id)->first();
        if ($registration) {
            $billing = Billing::where('course_id',$registration['course_id'])
                ->where('company_id', $registration['company_id'])
                ->where('is_bonus', $registration['is_bonus'])->first();
            if ($billing){
                if ($billing['number_students']-1 == 0) {
                    $billing->delete();
                } else {
                    $billing->update([
                        'number_students' => $billing['number_students']-1,
                        'billing' => $billing['billing'] - $registration['price']
                    ]);
                }

            }
            $tracing = Tracing::find($registration['tracing_id']);
            if ($tracing){
                $tracing->delete();
            }
            $chore = Chore::find($registration['chore_id']);
            if ($chore){
                $chore->delete();
            }
            $profitability = Profitability::find($registration['profitability_id']);
            if ($profitability){
                $profitability->delete();
            }
            $registration->delete();
        }
    }

    public function totalRegistrations(){
        $now = Carbon::now();
        $total = Registration::leftJoin('courses', 'registrations.course_id', '=', 'courses.id')->where('courses.beginning', '>=', $now->year.'-01-01')
            ->where('courses.beginning', '<=', $now->year.'-12-31')->get();
        return $total->count();
    }

    public function countRegistrations($start, $limit){
        $registrations = Registration::leftJoin('courses', 'registrations.course_id', '=', 'courses.id')->where('courses.beginning', '>=', $start)
            ->where('courses.beginning', '<=', $limit)->get();

        return $registrations->count();
    }

    public function getStudentCourses($id, $search_course_name, $search_group){
        $registations = Registration::select('courses.*')->leftJoin('courses', 'registrations.course_id', '=', 'courses.id')
            ->where('registrations.student_id', $id)
            ->where(function ($query) use ($search_course_name){
                $query->orWhere('courses.name', 'LIKE', $search_course_name);
            })->where(function ($query) use ($search_group){
                $query->orWhere('courses.group', 'LIKE', $search_group);
            })->get();
        foreach ($registations as $registation){
            $beginning = Carbon::parse($registation['beginning'])->format('d/m/Y');
            $registation['beginning'] = $beginning;
            $end = Carbon::parse($registation['end'])->format('d/m/Y');
            $registation['end'] = $end;
        }
        return $registations;
    }

    public function eliminateBill($id){
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

    public function billingRegistration($billing_id){
        $registrations = Registration::where('billing_id', $billing_id)->get();

        return $registrations;
    }

}

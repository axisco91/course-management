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

    public static function getRegistrated($course_id){
        $registations = Student::select('students.*', 'registrations.is_bonus', 'companies.name as company_name',
            'registrations.id as registration_id',
            DB::raw("CONCAT(students.name,' ',students.surname) as student"))
            ->leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
            ->leftJoin('companies', 'registrations.company_id', '=', 'companies.id')
            ->where('registrations.course_id', $course_id)->get();

        return $registations;
    }

    public static function getUnregistrated($course_id){
        $registations = Student::leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
            ->where('registrations.course_id', '=', $course_id)->pluck('student_id');
        $unregisted = Student::select('students.*', 'students.id as value', DB::raw("CONCAT(students.name,' ',students.surname) as label"))->where('active', 1)->whereNotIn('id', $registations)->get();

        return $unregisted;
    }

    public static function createRegistration($data){
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

    public static function unregistration($id, $course_id){
        $registration = Registration::where('course_id', $course_id)
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
                if ($registration['is_bonus'] == 1){
                    if ($profitability['number_students'] > 1){
                        $price = $profitability['price']-$registration['price'];
                        if ($profitability['advisor_percentage'] && $profitability['price']){
                            $advisor_commission = ($profitability['advisor_percentage'] / 100) * $price;
                        }
                        if ($profitability['collaborator_percentage'] && $profitability['price']){
                            $collaborator_commission = ($profitability['advisor_percentage'] / 100) * $price;
                        }
                        $prices = Profitability::getCalculateBenefits(GeneralHelpers::convertComa($price),
                            GeneralHelpers::convertComa($profitability['teacher']),
                            GeneralHelpers::convertComa($profitability['management']),
                            GeneralHelpers::convertComa($profitability['nebrija_title']),
                            GeneralHelpers::convertComa($profitability['discount']),
                            $collaborator_commission, $advisor_commission);
                        $profitability->update([
                            'number_students' => $profitability['number_students']-1,
                            'price' => $price,
                            'total' => $prices['total_cost'],
                            'benefits' => $prices['benefits'],
                            'advisor_commission' => $advisor_commission,
                            'collaborator_commission' => $collaborator_commission
                        ]);
                    } else{
                        $profitability->delete();
                    }
                } else{
                    $profitability->delete();
                }
            }
            $registration->delete();
        }
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

    public static function getStudentCourses($id){
        $registations = Registration::select('courses.*')->leftJoin('courses', 'registrations.course_id', '=', 'courses.id')
            ->where('registrations.student_id', $id)->get();
        foreach ($registations as $registation){
            $beginning = Carbon::parse($registation['beginning'])->format('d/m/Y');
            $registation['beginning'] = $beginning;
            $end = Carbon::parse($registation['end'])->format('d/m/Y');
            $registation['end'] = $end;
        }
        return $registations;
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

    public static function billingRegistration($billing_id){
        $registrations = Registration::where('billing_id', $billing_id)->get();

        return $registrations;
    }

}

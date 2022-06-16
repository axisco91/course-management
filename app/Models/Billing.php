<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $table = 'billings';

    protected $fillable = ['course_id','company_id','number_students','billing','bonus','total_training_activity','expenses','only_organizing_entity','salary_costs','payment_id','communication_start_date','communication_end_date','invoiced','billing_number','billing_date','collection_date','bonus_status','company_bonus','observation', 'is_bonus', 'student_id'];

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
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'id', 'payment_id');
    }

    public function getBillings($keyWord, $course_search, $company_search, $student_search, $is_bonus_search){
        $billings = Billing::latest()
            ->select('billings.*', 'training_actions.name as course', 'training_actions.formative_action as training_action', 'courses.group as group', 'companies.name as company', 'payments.name as payment')
            ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftjoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftjoin('students', 'students.id', '=', 'billings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id');

        if ($course_search != -1){
            $billings = $billings->where('billings.course_id', $course_search);
        }
        if ($company_search != -1){
            $billings = $billings->where('billings.company_id', $company_search);
        }
        if ($student_search != -1){
            $billings = $billings->where('billings.student_id', $student_search);
        }
        if ($is_bonus_search != -1){
            $billings = $billings->where('is_bonus', $is_bonus_search);
        }
        $billings = $billings->where(function ($query) use ($keyWord) {
                $query->orWhere('courses.name', 'LIKE', $keyWord)
                    ->orWhere('companies.name', 'LIKE', $keyWord)
                    ->orWhere('number_students', 'LIKE', $keyWord)
                    ->orWhere('billing', 'LIKE', $keyWord)
                    ->orWhere('bonus', 'LIKE', $keyWord)
                    ->orWhere('total_training_activity', 'LIKE', $keyWord)
                    ->orWhere('expenses', 'LIKE', $keyWord)
                    ->orWhere('only_organizing_entity', 'LIKE', $keyWord)
                    ->orWhere('salary_costs', 'LIKE', $keyWord)
                    ->orWhere('payments.name', 'LIKE', $keyWord)
                    ->orWhere('communication_start_date', 'LIKE', $keyWord)
                    ->orWhere('communication_end_date', 'LIKE', $keyWord)
                    ->orWhere('invoiced', 'LIKE', $keyWord)
                    ->orWhere('billing_number', 'LIKE', $keyWord)
                    ->orWhere('billing_date', 'LIKE', $keyWord)
                    ->orWhere('collection_date', 'LIKE', $keyWord)
                    ->orWhere('bonus_status', 'LIKE', $keyWord)
                    ->orWhere('company_bonus', 'LIKE', $keyWord)
                    ->orWhere('billings.observation', 'LIKE', $keyWord);
            })->orderBy('courses.beginning', 'desc')
            ->paginate(10);

        return $billings;
    }

    public function updateBilling($id, $data){
        $billing = Billing::find($id);

        if ($billing->communication_start_date != $data['communication_start_date']){
            Billing::updateCloseCommunicationDate($id, $data['communication_start_date'], 1);
        }
        if ($billing->communication_end_date != $data['communication_end_date']){
            Billing::updateCloseCommunicationDate($id, $data['communication_end_date'], 1);
        }

        $billing->update([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'number_students' => $data['number_students'],
            'billing' => GeneralHelpers::convertComa($data['billing']),
            'bonus' => GeneralHelpers::convertComa($data['bonus']),
            'total_training_activity' => $data['total_training_activity'],
            'expenses' => $data['expenses'],
            'only_organizing_entity' => $data['only_organizing_entity'],
            'salary_costs' => GeneralHelpers::convertComa($data['salary_costs']),
            'payment_id' => $data['payment_id'],
            'communication_start_date' => $data['communication_start_date'],
            'communication_end_date' => $data['communication_end_date'],
            'invoiced' => $data['invoiced'],
            'billing_number' => $data['billing_number'],
            'billing_date' => $data['billing_date'],
            'collection_date' => $data['collection_date'],
            'bonus_status' => $data['bonus_status'],
            'company_bonus' => $data['company_bonus'],
            'observation' => $data['observation'],
            'is_bonus' => $data['is_bonus'],
        ]);

        return $billing;
    }

    public function updateBillingRegistrations($data){
        $billing = Billing::where('course_id', $data['course_id'])
            ->where('company_id', $data['company_id'])
            ->where('is_bonus', $data['is_bonus'])->first();
        $company = Company::find($data['company_id']);

        if ($billing && $company['name'] != 'SIN EMPRESA'){
            $expenses = Billing::calculateExpenses($data['price'] + $billing['billing']);
            $billing->update([
                'number_students' => $billing['number_students']+1,
                'billing' => $data['price'] + $billing['billing'],
                'total_training_activity' => $data['price'] + $billing['billing'],
                'expenses' => $expenses
            ]);
        } else {
            $expenses = Billing::calculateExpenses($data['price']);
            $billing = Billing::create([
                'course_id' => $data['course_id'],
                'company_id' => $data['company_id'],
                'number_students' => 1,
                'is_bonus' => $data['is_bonus'],
                'billing' => $data['price'],
                'total_training_activity' => $data['price'],
                'expenses' => $expenses
            ]);
            if ($company['name'] != 'SIN EMPRESA'){
                $billing = $billing->update([
                    'student_id' => $data['student_id']
                ]);
            }
        }
    }

    public function calculateExpenses($precio){
        return (10/100) * $precio;
    }

    public function totalTrainingActivity($bonus, $expenses){
        return $bonus - $expenses;
    }

    public function updateStartCommunicationDate($id, $date, $status){
        $billing = Billing::find($id);
        $billing = $billing->update([
            'communication_start_date' => $date
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::updateCommunicationStartDate($registration->id, $date, $status);
        }
    }
    public function updateCloseCommunicationDate($id, $date, $status){
        $billing = Billing::find($id);
        $billing = $billing->update([
            'communication_end_date' => $date
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::updateCommunicationEndDate($registration->id, $date, $status);
        }
    }
}

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

    protected $fillable = ['course_id','company_id','number_students','billing','bonus','total_training_activity','expenses','only_organizing_entity','salary_costs','payment_id','communication_start_date','communication_end_date','invoiced','billing_number','billing_date','collection_date','bonus_status','company_bonus','observation', 'is_bonus', 'student_id', 'advisor_id', 'charged', 'collaborator_id'];

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

    public static function getBillings($keyWord, $course_search, $company_search, $student_search, $is_bonus_search, $status_search, $invoiced_search, $charged_search){
        $billings = Billing::latest()
            ->select('billings.*', 'training_actions.name as course', 'training_actions.formative_action as training_action',
                'courses.group as group', 'companies.name as company', 'payments.name as payment', 'advisors.name as advisor', 'courses.beginning as beginning')
            ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftjoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftjoin('students', 'students.id', '=', 'billings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'billings.advisor_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id');

        if ($status_search != -1){
            $billings = $billings->where('courses.course_status_id', '=', $status_search);
        } else{
            $billings = $billings->where('courses.course_status_id', '!=', 1);
        }
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
        if ($invoiced_search != -1){
            $billings = $billings->where('invoiced', $invoiced_search);
        }
        if ($charged_search != -1){
            $billings = $billings->where('charged', $charged_search);
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

    public static function updateBilling($id, $data){
        $billing = Billing::find($id);

        if ($billing->communication_start_date != $data['communication_start_date']){
            $status = 0;
            if ($data['communication_start_date']){
                $status = 1;
            }
            Billing::updateStartCommunicationDate($id, $data['communication_start_date'], $status);
        }
        if ($billing->communication_end_date != $data['communication_end_date']){
            $status = 0;
            if ($data['communication_end_date']){
                $status = 1;
            }
            Billing::updateCloseCommunicationDate($id, $data['communication_end_date'], $status);
        }

        $total_training_activity = Billing::totalTrainingActivity($data['bonus']);

        $billing->update([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'number_students' => $data['number_students'],
            'billing' => GeneralHelpers::convertComa($data['billing']),
            'bonus' => GeneralHelpers::convertComa($data['bonus']),
            'total_training_activity' => GeneralHelpers::convertComa($total_training_activity),
            'expenses' => GeneralHelpers::convertComa($data['expenses']),
            'only_organizing_entity' => $data['only_organizing_entity'],
            'salary_costs' => GeneralHelpers::convertComa($data['salary_costs']),
            'payment_id' => $data['payment_id'],
            'communication_start_date' => empty($data['communication_start_date']) ? null : $data['communication_start_date'],
            'communication_end_date' => empty($data['communication_end_date'])? null : $data['communication_end_date'],
            'invoiced' => $data['invoiced'],
            'billing_number' => $data['billing_number'],
            'billing_date' => empty($data['billing_date']) ? null : $data['billing_date'],
            'collection_date' => empty($data['collection_date']) ? null : $data['collection_date'],
            'bonus_status' => $data['bonus_status'],
            'company_bonus' => $data['company_bonus'],
            'observation' => $data['observation'],
            'is_bonus' => $data['is_bonus'],
            'advisor_id' => $data['advisor_id'],
            'charged' => $data['charged'] == '' ? 0 : 1,
            'collaborator_id' => $data['collaborator_id']
        ]);

        return $billing;
    }

    public function updateBillingRegistrations($data){
        $billing = Billing::where('course_id', $data['course_id'])
            ->where('company_id', $data['company_id'])
            ->where('is_bonus', $data['is_bonus'])->first();
        $company = Company::find($data['company_id']);

        if ($billing && $company['name'] != 'SIN EMPRESA'){
            $total_training_activity = GeneralHelpers::convertComa($data['price']) + $billing['billing'];
            $expenses = Billing::calculateExpenses($data['price'] + $billing['billing'], $total_training_activity);
            $billing->update([
                'number_students' => $billing['number_students']+1,
                'billing' => $data['price'] + $billing['billing'],
                'total_training_activity' => $total_training_activity,
                'expenses' => $expenses,
                'advisor_id' => $data['advisor_id'],
                'collaborator_id' => $data['collaborator_id']
            ]);
        } else {
            $total_training_activity = GeneralHelpers::convertComa($data['price']);
            $expenses = Billing::calculateExpenses($data['price'], $total_training_activity);
            $billing = Billing::create([
                'course_id' => $data['course_id'],
                'company_id' => $data['company_id'],
                'number_students' => 1,
                'is_bonus' => $data['is_bonus'],
                'billing' => GeneralHelpers::convertComa($data['price']),
                'total_training_activity' => $total_training_activity,
                'expenses' => $expenses,
                'advisor_id' => $data['advisor_id'],
                'collaborator_id' => $data['collaborator_id']
            ]);
            if ($company['name'] == 'SIN EMPRESA'){
                $billing->update([
                    'student_id' => $data['student_id']
                ]);
            }
        }
        return $billing;
    }

    public static function calculateExpenses($price, $total){
        return GeneralHelpers::convertComa($price - $total);
    }

    public static function totalTrainingActivity($bonus){
        return round($bonus / 1.1, 2);
    }

    public static function updateStartCommunicationDate($id, $date, $status){
        $billing = Billing::find($id);
        $billing->update([
            'communication_start_date' => empty($date) ? null : $date,
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::updateCommunicationStartDate($registration->chore_id, $date, $status);
        }
    }
    public static function updateCloseCommunicationDate($id, $date, $status){
        $billing = Billing::find($id);
        $billing->update([
            'communication_end_date' => empty($date) ? null : $date
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::updateCommunicationEndDate($registration->chore_id, $date, $status);
        }
    }

    public static function updateInvicedDate($id, $date, $status){
        $billing = Billing::find($id);
        $billing->update([
            'billing_date' => $date,
            'invoiced' => $status,
            'bonus_status' => $status
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::billingDateChore($registration->chore_id, $date, $status);
        }
    }
}
